<?php

namespace app\controllers;

use app\controllers\AccessController;
use app\models\AuthAssignment;
use app\models\ContactForm;
use app\models\PhoneLoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SmsCodeForm;
use app\models\SignupForm;
use app\models\User;
use app\helpers\CabinetHelper;
use app\services\Sms;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class SiteController extends AccessController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new PhoneLoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $tel = $model->getNormalizedTel();

            $user = User::findByTel($tel);
            if (!$user) {
                Yii::$app->session->setFlash('danger', 'Пользователь с таким номером не найден.');
                return $this->refresh();
            }

            // Генерируем код 6 цифр
            $code = (string)random_int(100000, 999999);
            $user->setSmsLoginCode($code);

            if (!$user->save(false, ['sms_code_hash', 'sms_code_sent_at'])) {
                Yii::$app->session->setFlash('danger', 'Не удалось сохранить код. Попробуйте ещё раз.');
                return $this->refresh();
            }

            try {
                (new Sms())->send($tel, "Код входа: {$code}");
            } catch (\Throwable $e) {
                Yii::error('SMS send failed: ' . $e->getMessage(), 'sms');
                Yii::$app->session->setFlash('danger', 'Не удалось отправить СМС. Попробуйте позже.');
                return $this->refresh();
            }

            Yii::$app->session->set('login.tel', $tel);
            return $this->redirect(['confirm-login']);
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionConfirmLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $tel = Yii::$app->session->get('login.tel');
        if (empty($tel)) {
            return $this->redirect(['login']);
        }

        $user = User::findByTel($tel);
        if (!$user) {
            Yii::$app->session->remove('login.tel');
            return $this->redirect(['login']);
        }

        $model = new SmsCodeForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $hash = $user->sms_code_hash;
            if (empty($hash)) {
                Yii::$app->session->setFlash('danger', 'Код не найден. Запросите новый код.');
                return $this->refresh();
            }

            $ok = Yii::$app->security->validatePassword($model->code, $hash);
            if (!$ok) {
                Yii::$app->session->setFlash('danger', 'Неверный код.');
                return $this->refresh();
            }

            $user->clearSmsLoginCode();
            $user->save(false, ['sms_code_hash', 'sms_code_sent_at']);
            Yii::$app->session->remove('login.tel');

            Yii::$app->user->login($user, 3600 * 24 * 30);
            return $this->redirect(CabinetHelper::getDefaultUrlForUser($user->id));
        }

        return $this->render('login-code', [
            'model' => $model,
            'telMasked' => $this->maskTel($tel),
        ]);
    }

    private function maskTel(string $tel): string
    {
        // tel: 7XXXXXXXXXX
        $digits = preg_replace('/\D+/', '', $tel);
        if (!preg_match('/^7\d{10}$/', $digits)) {
            return $tel;
        }
        $a = substr($digits, 1, 3);
        $b = substr($digits, 4, 3);
        $c = substr($digits, 7, 2);
        $d = substr($digits, 9, 2);
        return "+7 ({$a}) ***-**-{$d}";
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                $assignment = new AuthAssignment();
                $assignment->item_name = $model->role;
                $assignment->user_id = (string) $user->id;
                if (!$assignment->save()) {
                    Yii::$app->session->setFlash('danger', 'Не удалось установить права. Свяжитесь с технической поддержкой.');
                } else {
                    Yii::$app->session->setFlash('success', 'Регистрация завершена. Войдите в систему.');
                }
                return $this->redirect(['site/login']);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,]);
    }
}
