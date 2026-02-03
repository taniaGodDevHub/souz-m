<?php

namespace app\modules\advertiser;

/**
 * Личный кабинет рекламодателя.
 */
class Advertiser extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\advertiser\controllers';

    public function init()
    {
        parent::init();
        $this->layoutPath = '@app/modules/advertiser/views/layouts';
        $this->layout = 'main';
    }
}
