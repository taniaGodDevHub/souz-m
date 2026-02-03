<?php

namespace app\modules\admin;

/**
 * Личный кабинет администратора.
 */
class Admin extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function init()
    {
        parent::init();
        $this->layoutPath = '@app/modules/admin/views/layouts';
        $this->layout = 'main';
    }
}
