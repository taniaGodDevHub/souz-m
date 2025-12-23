<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\cars\models\CarMark $model */

$this->title = 'Создать марку';
$this->params['breadcrumbs'][] = ['label' => 'Марки автомобилей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-mark-create">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

