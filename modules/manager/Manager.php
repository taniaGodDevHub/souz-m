<?php

namespace app\modules\manager;

/**
 * Личный кабинет менеджера.
 */
class Manager extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\manager\controllers';

    public function init()
    {
        parent::init();
        $this->layoutPath = '@app/modules/manager/views/layouts';
        $this->layout = 'main';
    }
}
