<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\billing\models\Account;
use app\modules\billing\models\TransactionType;
use app\modules\billing\helpers\BillingHelper;

/** @var yii\web\View $this */
/** @var app\modules\billing\models\Transaction $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\modules\billing\controllers\BillingController $controller */
$controller = $this->context;
?>

<div class="transaction-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card">
        <div class="card-body">
            <?= $form->field($model, 'from_acc_id')->dropDownList(
                ArrayHelper::merge(['' => '— Не указан —'], $controller->getAvailableAccounts()),
                ['prompt' => 'Выберите счет отправителя']
            )->hint('Оставьте пустым для пополнения') ?>

            <?= $form->field($model, 'to_acc_id')->dropDownList(
                ArrayHelper::merge(['' => '— Не указан —'], $controller->getAvailableAccounts()),
                ['prompt' => 'Выберите счет получателя']
            )->hint('Оставьте пустым для списания') ?>

            <?= $form->field($model, 'transaction_type')->dropDownList(
                TransactionType::getList(),
                ['prompt' => 'Выберите тип транзакции']
            ) ?>

            <?= $form->field($model, 'amount_rubles')->textInput([
                'type' => 'number',
                'step' => '0.01',
                'value' => $model->isNewRecord ? '' : $model->getAmountInRubles()
            ])->label('Сумма (рубли)')->hint('Сумма будет автоматически преобразована в копейки') ?>

            <div class="form-group mt-3">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                <?php if ($model->to_acc_id): ?>
                    <?= Html::a('Отмена', ['view', 'id' => $model->to_acc_id], ['class' => 'btn btn-secondary']) ?>
                <?php elseif ($model->from_acc_id): ?>
                    <?= Html::a('Отмена', ['view', 'id' => $model->from_acc_id], ['class' => 'btn btn-secondary']) ?>
                <?php else: ?>
                    <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

