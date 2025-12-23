<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\cars\models\CarModel $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Модели автомобилей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-model-view">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex justify-content-between align-items-center">
                        <h1><?= Html::encode($this->title) ?></h1>
                        <div>
                            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить эту модель?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'mark_id',
                                'label' => 'Марка',
                                'value' => $model->mark ? Html::a($model->mark->name, ['/cars/car-mark/view', 'id' => $model->mark_id]) : '—',
                                'format' => 'raw',
                            ],
                            'name',
                            'name_cyrillic',
                            'class',
                            'year_from',
                            'year_to',
                            [
                                'attribute' => 'created_at',
                                'value' => $model->created_at ? date('d.m.Y H:i', $model->created_at) : '—',
                            ],
                            [
                                'attribute' => 'updated_at',
                                'value' => $model->updated_at ? date('d.m.Y H:i', $model->updated_at) : '—',
                            ],
                        ],
                    ]) ?>

                    <div class="mt-3">
                        <?= Html::a('Вернуться к списку', ['index'], ['class' => 'btn btn-secondary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

