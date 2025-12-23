<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\billing\models\Account $model */

$this->title = 'Редактировать счет: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Счета', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="account-update">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <?= $this->render('_form-account', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

