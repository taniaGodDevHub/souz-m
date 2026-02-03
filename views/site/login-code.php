<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\SmsCodeForm $model */
/** @var string $telMasked */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Код из СМС';
?>
<div class="site-login">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-5 ">
                <div class="card r-16">
                    <div class="card-body pt-5">
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <h3>Код из СМС</h3>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-md-10 text-center">
                                Мы отправили одноразовый код на <?= Html::encode($telMasked) ?>.<br>
                                Введите его в поле ниже.
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-md-8">
                                <?php $form = ActiveForm::begin([
                                    'id' => 'sms-code-form',
                                    'fieldConfig' => [
                                        'template' => "{label}\n{input}\n{error}",
                                        'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                                    ],
                                ]); ?>

                                <?= $form->field($model, 'code')->textInput([
                                    'autofocus' => true,
                                    'class' => 'mt-3 form-control',
                                    'id' => 'sms-code-input',
                                    'inputmode' => 'numeric',
                                    'maxlength' => 6,
                                ])->label(false) ?>

                                <div class="form-group">
                                    <div>
                                        <?= Html::submitButton('Подтвердить', ['class' => 'btn btn-danger', 'name' => 'confirm-button']) ?>
                                    </div>
                                </div>

                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
    const input = document.getElementById('sms-code-input');
    if (input) {
      input.addEventListener('input', function() {
        this.value = this.value.replace(/\\D+/g, '').slice(0, 6);
        if (this.value.length === 6) {
          const form = document.getElementById('sms-code-form');
          if (form) form.submit();
        }
      });
    }
JS);
?>

