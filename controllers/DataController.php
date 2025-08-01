<?php

namespace app\controllers;

use app\controllers\AccessController;
use app\models\AuthAssignment;
use app\models\City;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\Order;
use app\models\OrderProduct;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

class DataController extends AccessController
{
    public $enableCsrfValidation = false;
    public function actionGetCity()
    {
        $ids = [
            710,727,2883,1827,131,2519,2910,2152,1956,1234,2378,1325,1633,908,2656,1463,2571,1721,2377,1283,2732,
            1428,2190,3018/*минск*/, 794,3020,3021,3028,3023
        ];

        return $this->asJson(City::find()
            ->where(['id' => $ids])
            ->asArray()
            ->all());
    }
}
