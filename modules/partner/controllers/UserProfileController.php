<?php

namespace app\modules\partner\controllers;

use app\controllers\AccessController;
use app\models\Requisites;
use app\models\UserProfile;
use app\models\UserProfileForm;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Профиль пользователя в кабинете партнёра.
 */
class UserProfileController extends AccessController
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Просмотр профиля (редирект после сохранения).
     */
    public function actionView($id)
    {
        $profile = $this->findModel($id);
        $this->checkProfileAccess($profile);
        return $this->render('view', [
            'profile' => $profile,
        ]);
    }

    /**
     * Редактирование профиля текущего партнёра.
     */
    public function actionUpdate()
    {
        $profile = UserProfile::find()
            ->where(['user_id' => Yii::$app->user->identity->id])
            ->with('user')
            ->one();

        if (empty($profile)) {
            $profile = new UserProfile();
            $profile->user_id = Yii::$app->user->identity->id;
            $profile->save(false);
        }

        $model = new UserProfileForm();
        $model->loadFromProfile($profile);

        $requisitesForm = null;
        if ($this->request->isPost) {
            $post = $this->request->post();
            if (isset($post['Requisites'])) {
                $requisitesForm = new Requisites();
                $requisitesForm->user_id = Yii::$app->user->identity->id;
                if ($requisitesForm->load($post) && $requisitesForm->save()) {
                    return $this->redirect(Url::to(['update']) . '#requisites-tab-pane');
                }
            } elseif ($model->load($post) && $model->saveToProfile($profile)) {
                return $this->redirect(['update']);
            }
        }

        $requisites = Requisites::find()
            ->where(['user_id' => Yii::$app->user->identity->id])
            ->with('requisitesType')
            ->all();

        if ($requisitesForm === null && empty($requisites)) {
            $requisitesForm = new Requisites();
            $requisitesForm->user_id = Yii::$app->user->identity->id;
        }

        return $this->render('update', [
            'model' => $model,
            'profile' => $profile,
            'requisites' => $requisites,
            'requisitesForm' => $requisitesForm,
            'activateRequisitesTab' => $requisitesForm !== null && $requisitesForm->hasErrors(),
        ]);
    }

    protected function findModel($id)
    {
        $model = UserProfile::find()
            ->where(['id' => $id])
            ->with('user')
            ->one();
        if ($model !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Страница не найдена.');
    }

    protected function checkProfileAccess(UserProfile $profile)
    {
        if ($profile->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('Доступ запрещён.');
        }
    }
}
