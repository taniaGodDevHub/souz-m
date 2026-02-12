<?php

use app\models\RequisitesType;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Requisites $model */
/** @var yii\widgets\ActiveForm $form */

$typeList = RequisitesType::getList();
// ИП = тип 3 (Индивидуальный предприниматель), Физ. лицо = 1 (Физическое лицо), самозанятый 2
$typeIdIP = 3;
$typeIdsIndividual = [1, 2]; // Физическое лицо, Самозанятый
$currentTypeId = (int)($model->requisites_type_id ?? 0);
$isIP = $currentTypeId === $typeIdIP;
$paymentMethod = $model->payment_method ?: 'bank';
?>
<div class="requisites-form">
    <?php $form = ActiveForm::begin([
        'action' => ['update'],
        'method' => 'post',
        'options' => ['class' => 'requisites-add-form', 'id' => 'requisites-form'],
    ]); ?>

    <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'payment_method')->hiddenInput(['id' => 'requisites-payment-method', 'value' => $paymentMethod])->label(false) ?>

    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'requisites_type_id')->dropDownList($typeList, [
                'prompt' => 'Выберите правовую форму',
                'id' => 'requisites-type-id',
            ])->label(false) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'inn')->textInput(['maxlength' => 12, 'placeholder' => 'ИНН', 'class' => 'form-control requisites-input-inn'])->label(false) ?>
        </div>
    </div>
    <div class="row other-fields" style="<?= $currentTypeId ? '' : 'display:none' ?>">
        <div class="col-12 js-ip-only" style="<?= $isIP ? '' : 'display:none' ?>">
            <div class="row mt-3">
                <div class="col-12 col-md-6">
                    <?= $form->field($model, 'ogrn')->textInput(['maxlength' => 13, 'placeholder' => 'ОГРН/ОГРНИП', 'class' => 'form-control requisites-input-ogrn'])->label(false) ?>
                </div>
                <div class="col-12 col-md-6">
                    <?= $form->field($model, 'date_birth')->textInput(['maxlength' => 10, 'placeholder' => 'дд.мм.гггг', 'class' => 'form-control requisites-input-date_birth'])->label(false) ?>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 ">
                    <?= $form->field($model, 'ur_name')->textInput(['maxlength' => true, 'placeholder' => 'Наименование'])->label(false) ?>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 ">
                    <?= $form->field($model, 'registration_address')->textInput(['maxlength' => true, 'placeholder' => 'Адрес'])->label(false) ?>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-md-6">
                    <?= $form->field($model, 'nds')->dropDownList([
                        0 => 'Без НДС',
                        10 => 'НДС 10%',
                        20 => 'НДС 20%',
                    ], ['prompt' => 'Выберите ставку НДС'])->label(false) ?>
                </div>
            </div>

            <hr class="my-4">
            <h3 class="mt-3">Платежные реквизиты</h3>

            <div class="row mt-3">
                <div class="col-12 col-md-6">
                    <?= $form->field($model, 'rs')->textInput(['maxlength' => 20, 'placeholder' => 'Расчётный счёт', 'class' => 'form-control requisites-input-rs'])->label(false) ?>
                </div>
                <div class="col-12 col-md-6">
                    <?= $form->field($model, 'ks')->textInput(['maxlength' => 20, 'placeholder' => 'Корреспондентский счёт', 'class' => 'form-control requisites-input-ks'])->label(false) ?>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-md-6">
                    <?= $form->field($model, 'bik')->textInput(['maxlength' => 9, 'placeholder' => 'БИК', 'class' => 'form-control requisites-input-bik'])->label(false) ?>
                </div>
                <div class="col-12 col-md-6">
                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Банк'])->label(false) ?>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <input type="text" class="form-control" value="Банковский перевод" readonly disabled>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <input type="text" class="form-control" value="Российский рубль" readonly disabled>
                    </div>
                </div>
            </div>



        </div>

        <div class="col-12 js-fl-only" style="<?= $isIP ? 'display:none' : '' ?>">
            <div class="row">
                <div class="col-12">
                    <?= $form->field($model, 'signatory_fio')->textInput(['maxlength' => true, 'placeholder' => 'ФИО'])->label(false) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <?= $form->field($model, 'registration_address')->textInput(['maxlength' => true, 'placeholder' => 'Адрес'])->label(false) ?>
                </div>
                <div class="col-12 col-md-6">
                    <?= $form->field($model, 'date_birth')->textInput(['maxlength' => 10, 'placeholder' => 'дд.мм.гггг', 'class' => 'form-control requisites-input-date_birth'])->label(false) ?>
                </div>
            </div>

            <hr class="my-4">
            <h3 class="mt-3">Платежные реквизиты</h3>


            <div class="mt-3">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <button type="button" class="btn btn-outline-secondary requisites-payment-btn <?= $paymentMethod === 'sbp' ? 'active' : '' ?>" data-method="sbp">
                        <span class="me-1">СБП</span>
                        <small class="d-block text-muted">система быстрых платежей</small>
                    </button>
                    <button type="button" class="btn btn-outline-secondary requisites-payment-btn <?= $paymentMethod === 'bank' ? 'active' : '' ?>" data-method="bank">
                        По реквизитам
                    </button>
                </div>
            </div>

            <div class="js-payment-sbp" style="<?= $paymentMethod === 'sbp' ? '' : 'display:none' ?>">
                <div class="row mt-3">
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, 'tel')->textInput(['maxlength' => 18, 'placeholder' => '+7 (___) ___-__-__', 'class' => 'form-control requisites-input-tel'])->label(false) ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Банк'])->label(false) ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <input type="text" class="form-control" value="Банковский перевод" readonly disabled>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <input type="text" class="form-control" value="Российский рубль" readonly disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="js-payment-bank" style="<?= $paymentMethod === 'bank' ? '' : 'display:none' ?>">
                <div class="row mt-3">
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, 'rs')->textInput(['maxlength' => 20, 'placeholder' => 'Расчётный счёт', 'class' => 'form-control requisites-input-rs'])->label(false) ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, 'ks')->textInput(['maxlength' => 20, 'placeholder' => 'Корреспондентский счёт', 'class' => 'form-control requisites-input-ks'])->label(false) ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, 'bik')->textInput(['maxlength' => 9, 'placeholder' => 'БИК', 'class' => 'form-control requisites-input-bik'])->label(false) ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Банк'])->label(false) ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <input type="text" class="form-control" value="Банковский перевод" readonly disabled>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <input type="text" class="form-control" value="Российский рубль" readonly disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="d-flex gap-2 mt-4">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Отменить', ['update'], ['class' => 'btn btn-black']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJs(<<<JS
(function(){
    var typeSelect = document.getElementById('requisites-type-id');
    var paymentInput = document.getElementById('requisites-payment-method');
    var form = document.getElementById('requisites-form');
    var typeIdIP = 3;
    var typeIdsIndividual = [1, 2];

    function isIP() {
        var id = parseInt(typeSelect && typeSelect.value ? typeSelect.value : 0, 10);
        return id === typeIdIP;
    }
    function isVisible(el) {
        if (!el) return false;
        var s = window.getComputedStyle(el);
        return s.display !== 'none' && s.visibility !== 'hidden';
    }
    function setInputsDisabled(container, disabled) {
        if (!container) return;
        container.querySelectorAll('input:not([type="hidden"]), select, textarea').forEach(function(input) {
            input.disabled = disabled;
        });
    }
    function syncDisabledState() {
        var ip = isIP();
        var method = paymentInput ? paymentInput.value : 'bank';
        var ipBlock = form ? form.querySelector('.js-ip-only') : null;
        var flBlock = form ? form.querySelector('.js-fl-only') : null;
        setInputsDisabled(ipBlock, !ip);
        setInputsDisabled(flBlock, ip);
        if (flBlock && !ip) {
            var sbpBlock = flBlock.querySelector('.js-payment-sbp');
            var bankBlock = flBlock.querySelector('.js-payment-bank');
            setInputsDisabled(sbpBlock, method !== 'sbp');
            setInputsDisabled(bankBlock, method !== 'bank');
        }
    }
    function toggleTypeBlocks() {
        var ip = isIP();
        document.querySelectorAll('.js-ip-only').forEach(function(el) { el.style.display = ip ? '' : 'none'; });
        document.querySelectorAll('.js-fl-only').forEach(function(el) { el.style.display = ip ? 'none' : ''; });
        syncDisabledState();
    }
    function toggleOtherFields() {
        var hasType = typeSelect && typeSelect.value && typeSelect.value !== '';
        document.querySelectorAll('.other-fields').forEach(function(el) { el.style.display = hasType ? '' : 'none'; });
    }
    function setPaymentMethod(method) {
        if (paymentInput) paymentInput.value = method;
        document.querySelectorAll('.requisites-payment-btn').forEach(function(btn) { btn.classList.remove('active'); });
        var btn = document.querySelector('.requisites-payment-btn[data-method="' + method + '"]');
        if (btn) btn.classList.add('active');
        document.querySelectorAll('.js-payment-sbp').forEach(function(el) { el.style.display = method === 'sbp' ? '' : 'none'; });
        document.querySelectorAll('.js-payment-bank').forEach(function(el) { el.style.display = method === 'bank' ? '' : 'none'; });
        syncDisabledState();
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            toggleOtherFields();
            toggleTypeBlocks();
        });
    }
    document.querySelectorAll('.requisites-payment-btn').forEach(function(btn) {
        btn.addEventListener('click', function() { setPaymentMethod(this.getAttribute('data-method')); });
    });
    if (form) {
        form.addEventListener('submit', function() {
            syncDisabledState();
        });
    }
    toggleOtherFields();
    toggleTypeBlocks();

    if (typeof Inputmask !== 'undefined') {
        document.querySelectorAll('.requisites-input-inn').forEach(function(el) {
            new Inputmask({ mask: '999999999999', placeholder: '_', greedy: false }).mask(el);
        });
        document.querySelectorAll('.requisites-input-ogrn').forEach(function(el) {
            new Inputmask({ mask: '9999999999999', placeholder: '_' }).mask(el);
        });
        document.querySelectorAll('.requisites-input-date_birth').forEach(function(el) {
            new Inputmask({ mask: '99.99.9999', placeholder: '_' }).mask(el);
        });
        document.querySelectorAll('.requisites-input-rs').forEach(function(el) {
            new Inputmask({ mask: '99999999999999999999', placeholder: '_' }).mask(el);
        });
        document.querySelectorAll('.requisites-input-ks').forEach(function(el) {
            new Inputmask({ mask: '99999999999999999999', placeholder: '_' }).mask(el);
        });
        document.querySelectorAll('.requisites-input-bik').forEach(function(el) {
            new Inputmask({ mask: '999999999', placeholder: '_' }).mask(el);
        });
        document.querySelectorAll('.requisites-input-tel').forEach(function(el) {
            new Inputmask({ mask: '+7 (999) 999-99-99', placeholder: '_', clearIncomplete: true }).mask(el);
        });
    }

    function validateDateBirth(input) {
        var v = (input.value || '').replace(/\s/g, '');
        if (!v || v.length < 10) {
            input.setCustomValidity('');
            return;
        }
        var parts = v.split('.');
        if (parts.length !== 3) {
            input.setCustomValidity('Формат: дд.мм.гггг');
            return;
        }
        var dd = parseInt(parts[0], 10);
        var mm = parseInt(parts[1], 10);
        var yyyy = parseInt(parts[2], 10);
        var maxYear = new Date().getFullYear() - 18;
        if (isNaN(dd) || isNaN(mm) || isNaN(yyyy)) {
            input.setCustomValidity('Введите корректную дату');
            return;
        }
        if (dd < 1 || dd > 31) {
            input.setCustomValidity('День должен быть от 1 до 31');
            return;
        }
        if (mm < 1 || mm > 12) {
            input.setCustomValidity('Месяц должен быть от 1 до 12');
            return;
        }
        if (yyyy > maxYear) {
            input.setCustomValidity('Год рождения: не позднее ' + maxYear);
            return;
        }
        var d = new Date(yyyy, mm - 1, dd);
        if (d.getDate() !== dd || d.getMonth() !== mm - 1 || d.getFullYear() !== yyyy) {
            input.setCustomValidity('Некорректная дата');
            return;
        }
        input.setCustomValidity('');
    }
    document.querySelectorAll('.requisites-input-date_birth').forEach(function(el) {
        el.addEventListener('blur', function() { validateDateBirth(this); });
        el.addEventListener('input', function() { validateDateBirth(this); });
    });

    function validateInn(input) {
        var v = (input.value || '').replace(/\D/g, '');
        if (!v) {
            input.setCustomValidity('');
            return;
        }
        if (v.length !== 10 && v.length !== 12) {
            input.setCustomValidity('ИНН: 10 или 12 цифр');
            return;
        }
        input.setCustomValidity('');
    }
    document.querySelectorAll('.requisites-input-inn').forEach(function(el) {
        el.addEventListener('blur', function() { validateInn(this); });
        el.addEventListener('input', function() { validateInn(this); });
    });
})();
JS
, \yii\web\View::POS_READY);
?>
<?php
$this->registerCss(<<<CSS
.requisites-payment-btn { min-width: 140px; text-align: left; }
.requisites-payment-btn.active { border-color: #0d6efd; background: rgba(13, 110, 253, 0.1); }
CSS
);