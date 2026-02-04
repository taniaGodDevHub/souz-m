<?php
/** @var yii\web\View $this */
/** @var float $balance Сумма на счёте в рублях */
/** @var string $walletUrl Ссылка на страницу кошелька (по умолчанию заглушка #) */

use yii\bootstrap5\Html;

$formatted = number_format($balance, 2, ',', ' ');
?>
<div class="billing-balance-widget card card-grey r-16">
    <div class="card-body p-5">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <div class="row">
                    <div class="col-auto">
                        <img src="<?= Yii::getAlias('@web/img/billing-rouble.svg')?>" alt="">
                    </div>
                    <div class="col-auto">
                        <span class=" small text-danger">Всего доступно</span>
                        <div class="fs-5 fw-bold"><?= Html::encode($formatted) ?> ₽</div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <?= Html::a('Перейти в кошелёк', $walletUrl, ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
    </div>
</div>
