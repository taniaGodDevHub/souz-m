<?php

namespace app\modules\devLogin;

/**
 * Модуль быстрого входа под тестовыми пользователями (только для разработки).
 */
class DevLogin extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\devLogin\controllers';

    public function init()
    {
        parent::init();
    }
}
