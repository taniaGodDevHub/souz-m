<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserProfileForm $model */
/** @var yii\widgets\ActiveForm $form */

$editIcon = '<img src="' . Yii::getAlias('@web/img/edit-field.svg') . '" alt="Редактировать">';
?>
<div class="user-profile-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->identity->id])->label(false) ?>

    <div class="user-profile-form-field-row">
        <?= $form->field($model, 'f')->textInput(['maxlength' => true, 'disabled' => true, 'placeholder' => 'Фамилия'])->label(false) ?>
        <button type="button" class="user-profile-form-edit-btn" aria-label="Редактировать" title="Редактировать"><?= $editIcon ?></button>
    </div>

    <div class="user-profile-form-field-row">
        <?= $form->field($model, 'i')->textInput(['maxlength' => true, 'disabled' => true, 'placeholder' => 'Имя'])->label(false) ?>
        <button type="button" class="user-profile-form-edit-btn" aria-label="Редактировать" title="Редактировать"><?= $editIcon ?></button>
    </div>

    <div class="user-profile-form-field-row">
        <?= $form->field($model, 'o')->textInput(['maxlength' => true, 'disabled' => true, 'placeholder' => 'Отчество'])->label(false) ?>
        <button type="button" class="user-profile-form-edit-btn" aria-label="Редактировать" title="Редактировать"><?= $editIcon ?></button>
    </div>

    <div class="user-profile-form-field-row">
        <?= $form->field($model, 'tel')->textInput(['maxlength' => true, 'disabled' => true, 'placeholder' => 'Телефон'])->label(false) ?>
        <button type="button" class="user-profile-form-edit-btn" aria-label="Редактировать" title="Редактировать"><?= $editIcon ?></button>
    </div>

    <div class="user-profile-form-field-row">
        <?= $form->field($model, 'tg_login')->textInput(['maxlength' => true, 'disabled' => true, 'placeholder' => 'Telegram без @'])->label(false) ?>
        <button type="button" class="user-profile-form-edit-btn" aria-label="Редактировать" title="Редактировать"><?= $editIcon ?></button>
    </div>

    <div class="user-profile-form-field-row">
        <?= $form->field($model, 'password')->textInput(['maxlength' => true, 'disabled' => true, 'placeholder' => 'Пароль'])->label(false) ?>
        <button type="button" class="user-profile-form-edit-btn" aria-label="Редактировать" title="Редактировать"><?= $editIcon ?></button>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerCss(<<<CSS
.user-profile-form-field-row { position: relative; margin-bottom: 1rem; }
.user-profile-form-field-row .form-group { margin-bottom: 0; }
.user-profile-form-field-row .form-control { padding-right: 2.5rem; }
.user-profile-form-edit-btn {
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    width: 2rem;
    height: 2rem;
    padding: 0;
    border: none;
    background: none;
    color: #6c757d;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}
.user-profile-form-edit-btn:hover { color: #0d6efd; background: rgba(0,0,0,.05); }
.user-profile-form-field-row.is-editing .user-profile-form-edit-btn { display: none; }
CSS
);
$this->registerJs(<<<JS
(function(){
    document.querySelectorAll('.user-profile-form-edit-btn').forEach(function(btn){
        btn.addEventListener('click', function(){
            var row = this.closest('.user-profile-form-field-row');
            var input = row.querySelector('input:not([type="hidden"])');
            if (input) {
                input.removeAttribute('disabled');
                input.focus();
                row.classList.add('is-editing');
            }
        });
    });
})();
JS
, \yii\web\View::POS_READY);
