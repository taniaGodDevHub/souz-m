<?php

namespace app\modules\devLogin\controllers;

use app\helpers\CabinetHelper;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class DefaultController extends Controller
{
    /**
     * Автовход под пользователем test_[role]. Только в dev-окружении.
     */
    public function actionLogin($role)
    {
        if (!YII_ENV_DEV) {
            throw new ForbiddenHttpException('Доступ запрещён.');
        }

        $username = 'test_' . $role;
        $user = User::findByUsername($username);

        if (!$user) {
            Yii::$app->session->setFlash('danger', "Пользователь {$username} не найден. Выполните миграцию модуля devLogin.");
            return $this->redirect(Yii::$app->request->referrer ?: ['/site/index']);
        }

        Yii::$app->user->login($user, 3600 * 24 * 30);
        return $this->redirect(CabinetHelper::getDefaultUrlForUser($user->id));
    }
}
