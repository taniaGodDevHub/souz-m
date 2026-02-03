<?php

namespace app\modules\partner;

/**
 * Личный кабинет партнёра.
 */
class Partner extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\partner\controllers';

    public function init()
    {
        parent::init();
        $this->layoutPath = '@app/modules/partner/views/layouts';
        $this->layout = 'main';
    }
}
