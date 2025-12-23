<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\cars\models\CarModelSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Модели автомобилей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-model-index">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex justify-content-between align-items-center">
                        <h1><?= Html::encode($this->title) ?></h1>
                        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
                    </div>
                    <div class="sub-text mt-3">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'id',
                                [
                                    'attribute' => 'mark_id',
                                    'label' => 'Марка',
                                    'value' => function($model) {
                                        return $model->mark ? $model->mark->name : '—';
                                    },
                                    'filter' => \app\modules\cars\models\CarMark::getList(),
                                ],
                                'name:ntext:Название',
                                'name_cyrillic:ntext:Название (кириллица)',
                                'class:ntext:Класс',
                                [
                                    'attribute' => 'year_from',
                                    'label' => 'Год от',
                                ],
                                [
                                    'attribute' => 'year_to',
                                    'label' => 'Год до',
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
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
                </div>
            </div>
        </div>
    </div>
</div>

