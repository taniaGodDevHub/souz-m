<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\cars\models\CarModel $model */

$this->title = 'Создать модель';
$this->params['breadcrumbs'][] = ['label' => 'Модели автомобилей', 'url' => ['index']];
if (isset($_GET['mark_id'])) {
    $this->params['breadcrumbs'][] = ['label' => 'Марка', 'url' => ['/cars/car-mark/view', 'id' => $_GET['mark_id']]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-model-create">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <?php
                    if (isset($_GET['mark_id'])) {
                        $model->mark_id = (int)$_GET['mark_id'];
                    }
                    ?>
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

