<?php

namespace app\services;

use Yii;
use yii\httpclient\Client;

class Sms
{
    private Client $client;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);
    }

    /**
     * Отправить SMS через SMSC.ru.
     *
     * @param string $phone Телефон в формате 7XXXXXXXXXX
     * @param string $message Текст сообщения
     */
    public function send(string $phone, string $message): void
    {
        $cfg = Yii::$app->params['smsc'] ?? [];
        $login = $cfg['login'] ?? null;
        $password = $cfg['password'] ?? null;
        $apikey = $cfg['apikey'] ?? null;
        $sender = $cfg['sender'] ?? null;

        if (empty($apikey) && (empty($login) || empty($password))) {
            throw new \RuntimeException('SMSC credentials not configured (smsc.login/password or smsc.apikey)');
        }

        $params = [
            'phones' => $phone,
            'mes' => $message,
            'fmt' => 0, // plain text
            'charset' => 'utf-8',
            'err' => 1,
        ];

        if (!empty($sender)) {
            $params['sender'] = $sender;
        }

        if (!empty($apikey)) {
            $params['apikey'] = $apikey;
        } else {
            $params['login'] = $login;
            $params['psw'] = $password;
        }

        // SMSC: https://smsc.ru/sys/send.php
        $response = $this->client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://smsc.ru/sys/send.php')
            ->setData($params)
            ->send();

        if (!$response->isOk) {
            throw new \RuntimeException('SMSC request failed: HTTP ' . $response->statusCode);
        }

        $body = trim((string)$response->content);
        // fmt=0 errors look like: "ERROR = N (text)"
        if (stripos($body, 'ERROR') === 0) {
            throw new \RuntimeException('SMSC error: ' . $body);
        }

        Yii::info('SMSC send OK: ' . $body, 'sms');
    }
}

