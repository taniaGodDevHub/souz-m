<?php

use yii\helpers\Html;

/** @var app\models\Requisites $requisites */

$typeName = $requisites->requisitesType ? $requisites->requisitesType->name : '';
$title = trim($typeName . ' ' . ($requisites->ur_name ?: $requisites->ur_full_name ?: $requisites->signatory_fio ?: ''));
if ($title === '') {
    $title = 'Реквизиты';
}

$dateBirthFormatted = '';
if ($requisites->date_birth) {
    $d = \DateTime::createFromFormat('Y-m-d', $requisites->date_birth);
    if ($d === false) {
        $d = \DateTime::createFromFormat('d.m.Y', $requisites->date_birth);
    }
    $dateBirthFormatted = $d ? $d->format('d.m.Y') : '';
}

$ndsLabels = [
    0 => 'Без НДС',
    10 => 'НДС 10%',
    20 => 'НДС 20%',
];
$ndsText = $requisites->nds !== null && isset($ndsLabels[(int)$requisites->nds])
    ? $ndsLabels[(int)$requisites->nds]
    : ($requisites->nds !== null ? 'НДС ' . (int)$requisites->nds . '%' : 'Без НДС');

$registrationAddress = ''; // в модели нет поля «адрес регистрации»
?>
<div class="card card-grey card-shadow r-16 mb-4">
    <div class="card-body p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h3 class="mb-1"><?= Html::encode($title) ?></h3>
                <?php if ($typeName): ?>
                    <div class="text-body-secondary"><?= Html::encode($typeName) ?></div>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-link btn-sm p-2 text-secondary rounded-circle" title="Редактировать" aria-label="Редактировать">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                </svg>
            </button>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">ИНН</div>
                <div><?= Html::encode($requisites->inn ?: '—') ?></div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">ОГРН/ОГРНИП</div>
                <div><?= Html::encode($requisites->ogrn ?: '—') ?></div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">Дата рождения</div>
                <div><?= Html::encode($dateBirthFormatted ?: '—') ?></div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">Адрес регистрации</div>
                <div><?= Html::encode($registrationAddress ?: '—') ?></div>
            </div>
        </div>
        <div class="mb-4">
            <?= Html::a('Скачать оферту', '#', ['class' => 'text-danger text-decoration-underline']) ?>
        </div>

        <hr class="my-4">

        <div class="fw-bold mb-3">Дополнительная информация</div>
        <div class="row g-3 mb-0">
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">Сокращенное наименование</div>
                <div><?= Html::encode($requisites->ur_name ?: '—') ?></div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">Адрес регистрации</div>
                <div><?= Html::encode($registrationAddress ?: '—') ?></div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">Ставка НДС</div>
                <div><?= Html::encode($ndsText) ?></div>
            </div>
        </div>

        <hr class="my-4">

        <div class="fw-bold mb-3">Платежные реквизиты</div>
        <div class="row g-3 mb-0">
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">Расчётный счёт</div>
                <div><?= Html::encode($requisites->rs ?: '—') ?></div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">Корреспондентский счёт</div>
                <div><?= Html::encode($requisites->ks ?: '—') ?></div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">БИК</div>
                <div><?= Html::encode($requisites->bik ?: '—') ?></div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">Банк</div>
                <div><?= Html::encode($requisites->bank_name ?: '—') ?></div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">Тип платежа</div>
                <div>Банковский перевод</div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-body-secondary small">Валюта</div>
                <div>Российский рубль</div>
            </div>
        </div>
    </div>
</div>
