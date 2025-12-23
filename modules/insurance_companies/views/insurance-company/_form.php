<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\insurance_companies\models\InsuranceCompany $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="insurance-company-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true])->label('Полное наименование <span class="text-danger">*</span>') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'short_name')->textInput(['maxlength' => true])->label('Краткое наименование') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'previous_name')->textInput(['maxlength' => true])->label('Прежнее наименование') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'license_number')->textInput(['maxlength' => true])->label('Номер Лицензии Минфина') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'license_date')->textInput([
                        'type' => 'date',
                        'value' => $model->license_date ? date('Y-m-d', strtotime($model->license_date)) : ''
                    ])->label('Дата Лицензии Минфина') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'rsa_certificate_number')->textInput(['maxlength' => true])->label('Номер Свидетельства РСА') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'rsa_certificate_date')->textInput([
                        'type' => 'date',
                        'value' => $model->rsa_certificate_date ? date('Y-m-d', strtotime($model->rsa_certificate_date)) : ''
                    ])->label('Дата Свидетельства РСА') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'phone_fax')->textInput(['maxlength' => true])->label('Основной телефон/факс') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type' => 'email'])->label('E-mail общий') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'website')->textInput(['maxlength' => true])->label('Сайт') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'address')->textarea(['rows' => 3])->label('Адрес') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'inn')->textInput(['maxlength' => true])->label('ИНН') ?>
                </div>
            </div>

            <div class="form-group mt-3">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

