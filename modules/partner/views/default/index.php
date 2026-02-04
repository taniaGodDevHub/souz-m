<?php
/** @var yii\web\View $this */

use app\modules\billing\widgets\BalanceWidget;
use app\modules\partner\widgets\ArticleWidget;
use app\modules\stat\widgets\PartnerStatWidget;

$this->title = 'Кабинет партнёра';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-default-index">

    <div class="row">
        <div class="col-12">
            <h1>Здравствуйте, <?= Yii::$app->user->identity->username?> 🖐</h1>
        </div>
        <div class="col-12 mt-5">
                <?=  BalanceWidget::widget([
                    'userId' => Yii::$app->user->identity->id,
                    'walletUrl' => ['/billing/billing/index'],
                ]);?>
        </div>
        <div class="col-12 mt-5">
            <?=  PartnerStatWidget::widget([]);;?>
        </div>
        <div class="col-12 mt-5">
            <?= ArticleWidget::widget([]); ?>
        </div>
    </div>
</div>
