<?php

namespace app\controllers;


use app\models\User;
use app\models\UserProfile;
use Yii;

class TgController extends AccessController
{
    public $enableCsrfValidation = false;

    public $telegram = false;
    public $chat_id = false;
    public $username = false;
    public $command = false;
    public $clientFirstName = false;
    public $clientLastName = false;

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['Authorization'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        header('HTTP/1.1 200 OK');
        if (ob_get_level() > 0) {
            ob_flush();
        }
        flush();
        $this->telegram = Yii::$app->telegram;

        $postData = json_decode(file_get_contents('php://input'));
        Yii::info("Вебхук от ТG" . print_r($this->request->get(), true) . print_r($postData, true), 'tg');

        if (isset($this->telegram->input->message->text)) {
            $this->command = $this->telegram->input->message->text;
            $this->chat_id = $this->telegram->input->message->chat->id;
            $this->username = $this->telegram->input->message->chat->username;
            $this->clientFirstName = $this->telegram->input->message->chat->first_name;
        } else {
            return true;
        }

        switch ($this->command) {
            case '/start':

                $this->start();
                break;
            case '/connect':

                $this->connect_user();
                break;
            default:

                $this->unknown();

                break;
        }
        return true;
    }

    /**
     * Отправляет стартовое сообщение
     * @return void
     * @throws \yii\db\Exception
     */
    private function start()
    {

        $this->telegram->sendMessage([
            'chat_id' => $this->chat_id,
            'text' => "
Для связи аккаунта перейдите в панель управления и зарегистрируйтесь. Не забудьте указать логин этого аккаунта.
ВАЖНО! Логин при регистрации указывается без @
После регистрации вернитесь в этот чат и отправьте команду /connect
            ",
        ]);
        exit();
    }

    /**
     * Связывает аккаунт пользователя с аккаунтом ТГ
     * @return void
     * @throws \yii\db\Exception
     */
    private function connect_user()
    {
        $user = User::find()
            ->where(['tg_login' => $this->username])
            ->one();

        if (empty($user)) {
            $this->telegram->sendMessage([
                'chat_id' => $this->chat_id,
                'text' => "Не найден пользователь с таким логином телеграм. Добавьте логин тг в настройках профиля пользователя.",
            ]);
            exit();
        }

        $user->tg_id = (string)$this->chat_id;

        if (!$user->save()) {
            $this->telegram->sendMessage([
                'chat_id' => $this->chat_id,
                'text' => "Данные не сохранились. Попробуйте ещё раз или напишите с техническую поддержку." . print_r($user->getErrors(), true),
            ]);
        }
        $this->telegram->sendMessage([
            'chat_id' => $this->chat_id,
            'text' => "Всё сработало. Ваша учётная запись связана с этим чатом"
        ]);
        exit();
    }

    /**
     * Отвечает, что не знает такой команды.
     * @return void
     */
    private function unknown()
    {

                $this->telegram->sendMessage([
                    'chat_id' => $this->chat_id,
                    'text' => "Эта команда не поддерживается."
                ]);
                exit();


    }

}
