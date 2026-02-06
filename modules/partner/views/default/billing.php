<?php
/** @var yii\web\View $this */

use app\modules\billing\widgets\BalanceWidget;

$this->title = 'Финансы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-default-billing">
    <div class="row">
        <div class="col-12">
            <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>
        </div>
        <div class="col-12 mt-5">
            <?= BalanceWidget::widget([
                'userId' => Yii::$app->user->identity->id,
                'walletUrl' => ['/billing/billing/index'],
            ]) ?>
        </div>
    </div>
</div>
