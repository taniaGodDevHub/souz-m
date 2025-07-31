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
    public function actionNewOrder()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if(empty($data->client_name)){
            throw new BadRequestHttpException('client_name is invalid');
        }
        if(empty($data->client_phone)){
            throw new BadRequestHttpException('client_phone is invalid');
        }
        if(empty($data->type_order) || !in_array($data->type_order,['Konvert', 'Kovry', 'Semple'])){
            throw new BadRequestHttpException('type_order is invalid');
        }
        if(empty($data->type_order) ){
            throw new BadRequestHttpException('type_order is invalid');
        }
        if(empty($data->city_id) ){
            throw new BadRequestHttpException('city_id is invalid');
        }
        if(empty($data->products)){
            throw new BadRequestHttpException('products is invalid');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try{

            $order = new Order();
            $order->client_name = $data->client_name;
            $order->client_phone = $data->client_phone;
            $order->type_order = $data->type_order;
            $order->city_id = $data->city_id;

            if(!$order->save()){
                throw new BadRequestHttpException('save order error' . print_r($order->getErrors(), true));
            }

            foreach ($data->products as $product){
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
