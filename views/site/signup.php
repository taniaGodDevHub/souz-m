<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\SignupForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Регистрация';
?>
<div class="site-signup">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-5">
                <div class="card r-16">
                    <div class="card-body pt-5">
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <h3>Регистрация в кабинете партнера</h3>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-md-8">
                                <?php $form = ActiveForm::begin([
                                    'id' => 'form-signup',
                                    'fieldConfig' => [
                                        'template' => "{label}\n{input}\n{error}",
                                        'errorOptions' => ['class' => 'invalid-feedback'],
                                    ],
                                ]); ?>

                                <?= $form->field($model, 'fio')->textInput([
                                    'autofocus' => true,
                                    'class' => 'mt-3 form-control',
                                    'placeholder' => 'Фамилия Имя Отчество',
                                ])->label(false) ?>

                                <?= $form->field($model, 'username')->textInput([
                                    'class' => 'mt-3 form-control',
                                    'placeholder' => 'Имя в системе',
                                ])->label(false) ?>

                                <?= $form->field($model, 'tel')->textInput([
                                    'class' => 'mt-3 form-control',
                                    'id' => 'signup-tel-input',
                                    'placeholder' => 'Номер телефона',
                                ])->label(false) ?>

                                <?= $form->field($model, 'password')->passwordInput([
                                    'class' => 'mt-3 form-control',
                                    'placeholder' => 'Пароль',
                                ])->label(false) ?>

                                <div class="mt-3">
                                    <?= $form->field($model, 'agree_rules', [
                                        'template' => "{input}\n{error}",
                                        'options' => ['class' => 'form-group'],
                                    ])->checkbox([
                                        'label' => 'Я согласен с ' . Html::a('Правилами', Yii::getAlias('@web/docs/rules.pdf'), ['class' => 'link-danger', 'target' => '_blank']) . ' и ' . Html::a('Условиями передачи информации', Yii::getAlias('@web/docs/usloviya.pdf'), ['class' => 'link-danger', 'target' => '_blank']),
                                    ]) ?>
                                </div>

                                <?= Html::activeHiddenInput($model, 'role', ['value' => $model->role]) ?>

                                <div class="form-group mt-4">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-danger', 'name' => 'signup-button']) ?>
                                        <span class="text-muted">Уже есть личный кабинет? <?= Html::a('Войти', ['site/login'], ['class' => 'link-danger']) ?></span>
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
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js', [
    'depends' => [\yii\web\YiiAsset::class],
]);
$this->registerJs(<<<JS
    $('#signup-tel-input').inputmask('+7 (999) 999-99-99');
JS);
?>
