<?php

namespace app\modules\billing\controllers;

use app\controllers\AccessController;
use app\modules\billing\models\Account;
use app\modules\billing\models\Transaction;
use app\modules\billing\models\TransactionType;
use app\modules\billing\helpers\BillingHelper;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BillingController implements the CRUD actions for billing system.
 */
class BillingController extends AccessController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete-account' => ['POST'],
                        'delete-transaction' => ['POST'],
                        'topup-account' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Список счетов
     * @return string
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $isAdmin = Yii::$app->user->can('admin');

        $query = Account::find();

        if ($isAdmin) {
            // Администраторы видят свои счета + системные
            $query->where(['or',
                ['user_id' => $userId],
                ['and', ['user_id' => null], ['project_id' => null]],
            ]);
        } else {
            // Обычные пользователи видят только свои счета
            $query->where(['user_id' => $userId]);
        }

        $accounts = $query->orderBy(['name' => SORT_ASC])->all();

        return $this->render('index', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Просмотр счета
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $account = $this->findAccount($id);
        $this->checkAccountAccess($account);

        // Получаем все транзакции счета (входящие и исходящие)
        $incomingTransactions = $account->getIncomingTransactions()
            ->orderBy(['date_add' => SORT_DESC])
            ->all();

        $outgoingTransactions = $account->getOutgoingTransactions()
            ->orderBy(['date_add' => SORT_DESC])
            ->all();

        // Объединяем и сортируем
        $allTransactions = array_merge($incomingTransactions, $outgoingTransactions);
        usort($allTransactions, function($a, $b) {
            return $b->date_add - $a->date_add;
        });

        return $this->render('view', [
            'account' => $account,
            'transactions' => $allTransactions,
        ]);
    }

    /**
     * Создание счета
     * @return string|Response
     */
    public function actionCreateAccount()
    {
        $model = new Account();
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::info("Создан счет: {$model->name} (ID: {$model->id})", 'billing');
            Yii::$app->session->setFlash('success', 'Счет успешно создан.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create-account', [
            'model' => $model,
        ]);
    }

    /**
     * Редактирование счета
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateAccount($id)
    {
        $model = $this->findAccount($id);
        $this->checkAccountAccess($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::info("Обновлен счет: {$model->name} (ID: {$model->id})", 'billing');
            Yii::$app->session->setFlash('success', 'Счет успешно обновлен.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update-account', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление счета
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteAccount($id)
    {
        $model = $this->findAccount($id);
        $this->checkAccountAccess($model);

        // Проверка наличия транзакций
        $hasTransactions = $model->getIncomingTransactions()->exists() || 
                          $model->getOutgoingTransactions()->exists();

        if ($hasTransactions) {
            Yii::$app->session->setFlash('error', 'Нельзя удалить счет, у которого есть связанные транзакции.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->delete();
        Yii::info("Удален счет: {$model->name} (ID: {$model->id})", 'billing');
        Yii::$app->session->setFlash('success', 'Счет успешно удален.');

        return $this->redirect(['index']);
    }

    /**
     * Создание транзакции
     * @param int|null $account_id ID счета для предзаполнения
     * @return string|Response
     */
    public function actionCreateTransaction($account_id = null)
    {
        $model = new Transaction();
        
        if ($account_id) {
            $account = Account::findOne($account_id);
            if ($account) {
                $this->checkAccountAccess($account);
                $model->to_acc_id = $account_id;
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            // Проверка доступа к счетам
            if ($model->from_acc_id) {
                $fromAccount = Account::findOne($model->from_acc_id);
                if ($fromAccount) {
                    $this->checkAccountAccess($fromAccount);
                }
            }
            if ($model->to_acc_id) {
                $toAccount = Account::findOne($model->to_acc_id);
                if ($toAccount) {
                    $this->checkAccountAccess($toAccount);
                }
            }

            if ($model->save()) {
                Yii::info("Создана транзакция: ID {$model->id}, сумма: {$model->getAmountInRubles()} руб.", 'billing');
                Yii::$app->session->setFlash('success', 'Транзакция успешно создана.');
                
                // Редирект на просмотр счета
                if ($model->to_acc_id) {
                    return $this->redirect(['view', 'id' => $model->to_acc_id]);
                } elseif ($model->from_acc_id) {
                    return $this->redirect(['view', 'id' => $model->from_acc_id]);
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('create-transaction', [
            'model' => $model,
        ]);
    }

    /**
     * Редактирование транзакции
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateTransaction($id)
    {
        $model = $this->findTransaction($id);
        
        // Проверка доступа через счета
        if ($model->from_acc_id) {
            $this->checkAccountAccess($model->fromAccount);
        }
        if ($model->to_acc_id) {
            $this->checkAccountAccess($model->toAccount);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::info("Обновлена транзакция: ID {$model->id}", 'billing');
            Yii::$app->session->setFlash('success', 'Транзакция успешно обновлена.');
            
            if ($model->to_acc_id) {
                return $this->redirect(['view', 'id' => $model->to_acc_id]);
            } elseif ($model->from_acc_id) {
                return $this->redirect(['view', 'id' => $model->from_acc_id]);
            }
            return $this->redirect(['index']);
        }

        return $this->render('update-transaction', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление транзакции
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteTransaction($id)
    {
        $model = $this->findTransaction($id);
        
        // Проверка доступа через счета
        if ($model->from_acc_id) {
            $this->checkAccountAccess($model->fromAccount);
        }
        if ($model->to_acc_id) {
            $this->checkAccountAccess($model->toAccount);
        }

        $accountId = $model->to_acc_id ?: $model->from_acc_id;
        $model->delete();
        
        Yii::info("Удалена транзакция: ID {$id}", 'billing');
        Yii::$app->session->setFlash('success', 'Транзакция успешно удалена.');

        if ($accountId) {
            return $this->redirect(['view', 'id' => $accountId]);
        }
        return $this->redirect(['index']);
    }

    /**
     * AJAX пополнение счета
     * @return Response
     */
    public function actionTopupAccount()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $accountId = Yii::$app->request->post('account_id');
        $amount = (int)Yii::$app->request->post('amount'); // в копейках

        if (!$accountId || !$amount || $amount <= 0) {
            return [
                'success' => false,
                'message' => 'Неверные параметры запроса',
            ];
        }

        $account = Account::findOne($accountId);
        if (!$account) {
            return [
                'success' => false,
                'message' => 'Счет не найден',
            ];
        }

        $this->checkAccountAccess($account);

        $transaction = new Transaction();
        $transaction->to_acc_id = $accountId;
        $transaction->from_acc_id = null; // Пополнение
        $transaction->transaction_type = TransactionType::find()
            ->where(['name' => 'Пополнение счета'])
            ->one()->id ?? 1;
        $transaction->amount = $amount;

        if ($transaction->save()) {
            Yii::info("Пополнен счет: ID {$accountId}, сумма: " . BillingHelper::kopecksToRubles($amount) . " руб.", 'billing');
            return [
                'success' => true,
                'message' => 'Счет успешно пополнен',
                'balance' => $account->getBalanceInRubles(),
            ];
        } else {
            Yii::error("Ошибка при пополнении счета: " . print_r($transaction->getErrors(), true), 'billing');
            return [
                'success' => false,
                'message' => 'Ошибка при создании транзакции: ' . implode(', ', $transaction->getFirstErrors()),
            ];
        }
    }

    /**
     * Найти счет по ID
     * @param int $id
     * @return Account
     * @throws NotFoundHttpException
     */
    protected function findAccount($id)
    {
        if (($model = Account::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Счет не найден.');
    }

    /**
     * Найти транзакцию по ID
     * @param int $id
     * @return Transaction
     * @throws NotFoundHttpException
     */
    protected function findTransaction($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Транзакция не найдена.');
    }

    /**
     * Проверка доступа к счету
     * @param Account $account
     * @throws NotFoundHttpException
     */
    protected function checkAccountAccess($account)
    {
        $userId = Yii::$app->user->id;
        $isAdmin = Yii::$app->user->can('admin');

        // Администраторы имеют доступ ко всем счетам
        if ($isAdmin) {
            return;
        }

        // Системные счета доступны только администраторам
        if ($account->isSystem()) {
            throw new NotFoundHttpException('Доступ запрещен.');
        }

        // Пользователи могут видеть только свои счета
        if ($account->isUser() && $account->user_id != $userId) {
            throw new NotFoundHttpException('Доступ запрещен.');
        }

        // Для счетов проектов нужна дополнительная проверка прав доступа к проекту
        // TODO: реализовать проверку доступа к проекту
        if ($account->isProject()) {
            // Пока разрешаем доступ всем авторизованным пользователям
            // В будущем здесь должна быть проверка прав доступа к проекту
        }
    }

    /**
     * Получить список доступных счетов для текущего пользователя
     * @return array
     */
    public function getAvailableAccounts()
    {
        $userId = Yii::$app->user->id;
        $isAdmin = Yii::$app->user->can('admin');

        $query = Account::find();

        if ($isAdmin) {
            // Администраторы видят все счета
            $accounts = $query->orderBy(['name' => SORT_ASC])->all();
        } else {
            // Обычные пользователи видят только свои счета
            $accounts = $query->where(['user_id' => $userId])
                ->orderBy(['name' => SORT_ASC])
                ->all();
        }

        return \yii\helpers\ArrayHelper::map($accounts, 'id', 'name');
    }
}

