<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\modules\cars\models\CarModel;

/** @var yii\web\View $this */
/** @var app\modules\cars\models\CarMark $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Марки автомобилей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-mark-view">
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
                                    'confirm' => 'Вы уверены, что хотите удалить эту марку? Все связанные модели также будут удалены.',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'name',
                            'name_cyrillic',
                            [
                                'attribute' => 'is_popular',
                                'value' => $model->is_popular ? 'Да' : 'Нет',
                            ],
                            'country',
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

                    <div class="mt-4">
                        <h5>Модели этой марки (<?= $model->getModelsCount() ?>)</h5>
                        <?= Html::a('Добавить модель', ['/cars/car-model/create', 'mark_id' => $model->id], ['class' => 'btn btn-sm btn-success mb-3']) ?>
                        <?= GridView::widget([
                            'dataProvider' => new \yii\data\ActiveDataProvider([
                                'query' => $model->getModels(),
                                'pagination' => ['pageSize' => 20],
                            ]),
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'name',
                                'name_cyrillic',
                                'class',
                                'year_from',
                                'year_to',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'controller' => 'car-model',
                                    'template' => '{view} {update} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            return Html::a('<i class="bi bi-eye"></i>', $url, [
                                                'class' => 'btn btn-sm btn-primary',
                                                'title' => 'Просмотр',
                                            ]);
                                        },
                                        'update' => function ($url, $model) {
                                            return Html::a('<i class="bi bi-pencil"></i>', $url, [
                                                'class' => 'btn btn-sm btn-warning',
                                                'title' => 'Редактировать',
                                            ]);
                                        },
                                        'delete' => function ($url, $model) {
                                            return Html::a('<i class="bi bi-trash"></i>', $url, [
                                                'class' => 'btn btn-sm btn-danger',
                                                'title' => 'Удалить',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите удалить эту модель?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                    ],
                                ],
                            ],
                        ]); ?>
                    </div>

                    <div class="mt-3">
                        <?= Html::a('Вернуться к списку', ['index'], ['class' => 'btn btn-secondary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

