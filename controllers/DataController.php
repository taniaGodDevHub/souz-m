<?php

namespace app\controllers;

use app\controllers\AccessController;
use app\models\AuthAssignment;
use app\models\City;
use app\models\ContactForm;
use app\models\Dealers;
use app\models\LoginForm;
use app\models\Order;
use app\models\OrderProduct;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\db\Exception;
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

    /**
     * Получает заказ и отправляет сообщение в ТГ соответствующему дилеру
     * @return void
     * @throws BadRequestHttpException
     * @throws Exception
     */
    public function actionSetOrder()
    {
        /*{
              client_name: "",
              client_phone: "",
              products: [
                {
                  title: "Neiron 900",
                },
              ],
              type_order: 'Semple', //Может быть Konvert, Kovry, Semple
              diller_id: 1
          }*/
        if(!$this->request->isPost){
            throw new BadRequestHttpException("Only POST request is allowed");
        }
        $data = json_decode(file_get_contents('php://input'), true);

        $transaction = Yii::$app->db->beginTransaction();
        try{
            $order = new Order();
            $order->client_name = $data['client_name'];
            $order->client_phone = $data['client_phone'];
            $order->dealer_id = $data['dealer_id'];
            $order->type_order = $data['type_order'];

            if(!$order->save()){
                throw new BadRequestHttpException("Cant create order" . print_r($order->getErrors(), true));
            }

            foreach ($data['products'] as $product){

                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->name = $product['title'];

                if(!$orderProduct->save()){
                    throw new BadRequestHttpException("Cant create order product" . print_r($orderProduct->getErrors(), true));
                }
            }
            $transaction->commit();

        }catch (\Exception $e){
            $transaction->rollBack();
            throw new Exception($e);

        }

        //Формируем сообщение
        $msg =
            "Новый заказ.
Клиент: ".$order->client_name.": ".$order->client_phone."
Тип: ".$order->type_order."
Товары: 

";
        foreach (OrderProduct::find()->where(['order_id' => $order->id])->all() as $product){
            $msg .= $product->name."\n";
        }

        //Ищем дилера
        $dealer = Dealers::find()
            ->where(['id' => $order->dealer_id])
            ->with('profileWithUser')
            ->one();

        if(!empty($dealer) && !empty($dealer->profileWithUser) && !empty($dealer->profileWithUser->user)){

            $user = $dealer->profileWithUser->user;
        }else{
            $user = User::find()
                ->where(['username' => 'admin'])
                ->one();
        }

        $this->telegram = Yii::$app->telegram;
        $this->telegram->sendMessage([
            'chat_id' => $user->tg_id,
            'text' => $msg
        ]);
    }
}
