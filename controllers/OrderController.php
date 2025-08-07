<?php

namespace app\controllers;

use app\controllers\AccessController;
use app\models\AuthAssignment;
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

class OrderController extends AccessController
{
    public $enableCsrfValidation = false;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    '*' => ['options','get','post'], // разрешаем OPTION-запросы ко всем действиям
                ],
            ],
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => [],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                header('Access-Control-Allow-Origin: *');
                header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
                header('Access-Control-Allow-Headers: Content-Type, Authorization');
                header('Access-Control-Allow-Credentials: true');
                die(); // останавливаем дальнейшее выполнение
            }
            return true;
        }
        return false;
    }

    public function actionNewOrder()
    {
        $data = json_decode(file_get_contents('php://input'), true);



        if(!isset($data['client_name'])){
            throw new BadRequestHttpException('client_name is invalid');
        }
        if(!isset($data['client_phone'])){
            throw new BadRequestHttpException('client_phone is invalid');
        }
        if(!isset($data['type_order']) || !in_array($data['type_order'],['konvert', 'kovry', 'semple'])){
            throw new BadRequestHttpException('type_order is invalid');
        }
        if(!isset($data['dealer_id']) ){
            throw new BadRequestHttpException('dealer_id is invalid');
        }
        if(!isset($data['products'])){
            throw new BadRequestHttpException('products is invalid');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try{

            $order = new Order();
            $order->client_name = $data['client_name'];
            $order->client_phone = $data['client_phone'];
            $order->type_order = $data['type_order'];
            $order->dealer_id = $data['dealer_id'];

            if(!$order->save()){
                throw new BadRequestHttpException('save order error' . print_r($order->getErrors(), true));
            }

            foreach ($data['products'] as $product){
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->name = $order->title;
                if(!$orderProduct->save()){
                    throw new BadRequestHttpException('save order product error' . print_r($orderProduct->getErrors(), true));
                }
            }

            $transaction->commit();
        }catch (\Throwable $e){
            $transaction->rollBack();
            throw $e;
        }
    }
}
