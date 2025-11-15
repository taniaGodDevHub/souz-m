<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Dealers $model */

$this->title = 'Изменить дилерский центр: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Дилерские центры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="dealers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

