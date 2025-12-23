<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\insurance_companies\models\InsuranceCompany $model */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Страховые компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-company-view">
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
                                    'confirm' => 'Вы уверены, что хотите удалить эту страховую компанию?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'full_name',
                            'short_name',
                            'previous_name',
                            'license_number',
                            [
                                'attribute' => 'license_date',
                                'value' => $model->formatDate($model->license_date),
                            ],
                            'rsa_certificate_number',
                            [
                                'attribute' => 'rsa_certificate_date',
                                'value' => $model->formatDate($model->rsa_certificate_date),
                            ],
                            'phone_fax',
                            'email:email',
                            'website:url',
                            'address:ntext',
                            'inn',
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

