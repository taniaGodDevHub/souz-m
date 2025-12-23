<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\cars\models\CarMark;

/** @var yii\web\View $this */
/** @var app\modules\cars\models\CarModel $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="car-model-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'mark_id')->dropDownList(
                        CarMark::getList(),
                        ['prompt' => 'Выберите марку']
                    )->label('Марка <span class="text-danger">*</span>') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Название модели (латиница) <span class="text-danger">*</span>') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name_cyrillic')->textInput(['maxlength' => true])->label('Название модели (кириллица)') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'class')->textInput(['maxlength' => true])->label('Класс автомобиля') ?>
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
                <?php if ($model->mark_id): ?>
                    <?= Html::a('Отмена', ['/cars/car-mark/view', 'id' => $model->mark_id], ['class' => 'btn btn-secondary']) ?>
                <?php else: ?>
                    <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

