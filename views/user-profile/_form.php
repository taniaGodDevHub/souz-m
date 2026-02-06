<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserProfileForm $model */
/** @var yii\widgets\ActiveForm $form */

$editIcon = '<img src="'. Yii::getAlias('@web/img/edit-field.svg').'" alt="Редактировать">';
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
        <?= $form->field($model, 'tel')->textInput(['maxlength' => 18, 'disabled' => true, 'placeholder' => '+7 (___) ___-__-__', 'class' => 'form-control user-profile-input-tel'])->label(false) ?>
        <button type="button" class="user-profile-form-edit-btn" aria-label="Редактировать" title="Редактировать"><?= $editIcon ?></button>
    </div>

    <div class="user-profile-form-field-row">
        <?= $form->field($model, 'tg_login')->textInput(['maxlength' => 32, 'disabled' => true, 'placeholder' => 'Telegram без @', 'class' => 'form-control user-profile-input-tg'])->label(false) ?>
        <button type="button" class="user-profile-form-edit-btn" aria-label="Редактировать" title="Редактировать"><?= $editIcon ?></button>
    </div>

    <div class="user-profile-form-field-row">
        <?= $form->field($model, 'password')->textInput(['maxlength' => true, 'disabled' => true, 'placeholder' => 'Пароль'])->label(false) ?>
        <button type="button" class="user-profile-form-edit-btn" aria-label="Редактировать" title="Редактировать"><?= $editIcon ?></button>
    </div>

    <div class="form-group d-flex justify-content-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js', ['position' => \yii\web\View::POS_HEAD]);
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
    // Маска телефона: +7 (XXX) XXX-XX-XX (vanilla Inputmask 5)
    var telInputs = document.querySelectorAll('.user-profile-input-tel');
    if (typeof Inputmask !== 'undefined') {
        telInputs.forEach(function(el) {
            new Inputmask({ mask: '+7 (999) 999-99-99', placeholder: '_', clearIncomplete: true }).mask(el);
        });
    }
    // Telegram: только латиница, цифры, подчёркивание (без @), до 32 символов
    document.querySelectorAll('.user-profile-input-tg').forEach(function(el) {
        el.addEventListener('input', function() {
            var v = this.value.replace(/[^a-zA-Z0-9_]/g, '').slice(0, 32);
            if (v !== this.value) this.value = v;
        });
    });
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
