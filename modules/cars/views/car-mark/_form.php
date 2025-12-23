<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\cars\models\CarMark $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="car-mark-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Название марки (латиница) <span class="text-danger">*</span>') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'name_cyrillic')->textInput(['maxlength' => true])->label('Название марки (кириллица)') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'is_popular')->checkbox()->label('Популярная марка') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'country')->textInput(['maxlength' => true])->label('Страна') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'year_from')->textInput(['type' => 'number'])->label('Год начала производства') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'year_to')->textInput(['type' => 'number'])->label('Год окончания производства') ?>
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

