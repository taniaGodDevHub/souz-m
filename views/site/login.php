<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\PhoneLoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Вход';
?>
<div class="site-login">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-5 ">
                <div class="card r-16">
                    <div class="card-body pt-5 pb-5">
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <h3>Вход в кабинет партнера</h3>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-auto">
                                Введите номер телефона чтобы войти
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-md-8">
                                <?php $form = ActiveForm::begin([
                                    'id' => 'login-form',
                                    'fieldConfig' => [
                                        'template' => "{label}\n{input}\n{error}",
                                        'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                                    ],
                                ]); ?>

                                <?= $form->field($model, 'tel')->textInput([
                                    'autofocus' => true,
                                    'class' => 'mt-3 form-control',
                                    'id' => 'tel-input',
                                ])->label(false) ?>


                                <div class="form-group">
                                    <div>
                                        <?= Html::submitButton('Продолжить', ['class' => 'btn btn-danger', 'name' => 'login-button']) ?>
                                    </div>
                                </div>

                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="row justify-content-start">
                                    <div class="col-md-auto">
                                        <?= Html::a('Зарегистрироваться', ['site/signup'], ['class' => 'link-danger']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Inputmask через CDN (jQuery есть в YiiAsset)
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js', [
    'depends' => [\yii\web\YiiAsset::class],
]);
$this->registerJs(<<<JS
    $('#tel-input').inputmask('+7 (999) 999-99-99');
JS);
?>

