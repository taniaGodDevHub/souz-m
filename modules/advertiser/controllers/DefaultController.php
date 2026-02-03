<?php

namespace app\modules\advertiser\controllers;

use app\controllers\AccessController;
use Yii;

class DefaultController extends AccessController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
