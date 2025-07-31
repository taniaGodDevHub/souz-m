<?php

namespace app\commands;

use app\models\City;
use app\models\Dealers;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\httpclient\Client;

class DataController extends Controller
{

    public function actionGetCities()
    {
        $client = new Client();
        $result =  $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://souz-m.ru/getCities.php')
            ->send();

        $result = json_decode($result->getContent());

        foreach ($result as $city) {

            $model = City::find()->where(['id' => $city->Message_ID])->one();
            if(empty($model)) {
                $model = new City();
            }

            $model->id = $city->Message_ID;
            $model->name = $city->CityName;
            $model->save();
        }
        return ExitCode::OK;
    }

    public function actionGetDealers()
    {
        $client = new Client();
        $result =  $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://souz-m.ru/getDealers.php')
            ->send();
        //var_dump($result->getContent());
        $result = json_decode($result->getContent());

        foreach ($result as $dealer) {

            $model = Dealers::find()->where(['id' => $dealer->Message_ID])->one();
            if(empty($model)) {
                $model = new Dealers();
            }

            $model->city_id = $dealer->City_ID;
            $model->name = $dealer->Name;
            $model->address  = $dealer->Address;
            $model->phone  = $dealer->Phone;
            $model->email  = $dealer->Email;
            $model->save();
        }
        return ExitCode::OK;
    }
}
