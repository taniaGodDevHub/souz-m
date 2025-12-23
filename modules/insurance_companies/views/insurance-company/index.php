<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\insurance_companies\models\InsuranceCompanySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Страховые компании';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-company-index">
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
                                'full_name:ntext:Полное наименование',
                                'short_name:ntext:Краткое наименование',
                                'license_number:ntext:Номер Лицензии',
                                [
                                    'attribute' => 'license_date',
                                    'format' => 'date',
                                    'label' => 'Дата Лицензии',
                                ],
                                'rsa_certificate_number:ntext:Номер Свид-ва РСА',
                                [
                                    'attribute' => 'rsa_certificate_date',
                                    'format' => 'date',
                                    'label' => 'Дата Свид-ва РСА',
                                ],
                                'phone_fax:ntext:Телефон/факс',
                                'email:email',
                                'website:url:Сайт',
                                'inn:ntext:ИНН',
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
                                                    'confirm' => 'Вы уверены, что хотите удалить эту страховую компанию?',
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

