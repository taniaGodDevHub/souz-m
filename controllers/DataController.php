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
    public function actionGetCity()
    {
        $ids = [
            2883,1827,889,2519,2910,2152,1956,1235,2378,1325,1633,908,2656,1463,2571,1721,2377,1283,2732,
            1428,2190,3018/*минск*/, 794,3020,3021,3028,3023,1427,2287, 2644
        ];

        return $this->asJson(City::find()
            ->where(['id' => $ids])
            ->asArray()
            ->all());
    }
    public function actionGetDealerByCityId($city_id)
    {


        return $this->asJson(Dealers::find()
            ->where(['city_id' => $city_id])
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

        if(!isset($data['dealer_id'])){
            throw new BadRequestHttpException("Dealer_id is required");
        }
        if(!isset($data['client_name'])){
            throw new BadRequestHttpException("Client_name is required");
        }
        if(!isset($data['client_phone'])){
            throw new BadRequestHttpException("Client_phone is required");
        }
        if(!isset($data['type_order'])){
            throw new BadRequestHttpException("Type_order is required");
        }
        if(!isset($data['products'])){
            throw new BadRequestHttpException("Products is required");
        }
        //print_r($data);die();
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

        if(!empty($dealer)){
            if(!empty($dealer->profileWithUser) && !empty($dealer->profileWithUser->user)){
                $user = $dealer->profileWithUser->user;
            }else{
                $msg .= "\n !!!! В системе нет аккаунта TG для запрошенного дилера: " . $dealer->name ."!!!!!";
                $user = User::find()
                    ->where(['username' => 'admin'])
                    ->one();
            }

        }else{
            $msg .= "\n !!!! В системе нет дилера c ID: " . $order->dealer_id ."!!!!!";
            $user = User::find()
                ->where(['username' => 'admin'])
                ->one();
        }

        $telegram = Yii::$app->telegram;
        $telegram->sendMessage([
            'chat_id' => $user->tg_id,
            'text' => $msg
        ]);
    }

}
