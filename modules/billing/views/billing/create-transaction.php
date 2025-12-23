<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\billing\models\Transaction $model */

$this->title = 'Создать транзакцию';
$this->params['breadcrumbs'][] = ['label' => 'Счета', 'url' => ['index']];
if ($model->to_acc_id) {
    $this->params['breadcrumbs'][] = ['label' => 'Счет', 'url' => ['view', 'id' => $model->to_acc_id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-create">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <?= $this->render('_form-transaction', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

