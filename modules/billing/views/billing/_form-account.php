<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\billing\models\Account $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="account-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card">
        <div class="card-body">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?php if (!$model->isNewRecord): ?>
                <?= $form->field($model, 'user_id')->textInput(['readonly' => true]) ?>
                <?= $form->field($model, 'project_id')->textInput(['readonly' => true]) ?>
            <?php endif; ?>

            <div class="form-group mt-3">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

