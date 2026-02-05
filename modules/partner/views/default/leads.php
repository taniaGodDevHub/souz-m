<?php
/** @var yii\web\View $this */
/** @var app\models\LeadForm $leadForm */
/** @var bool $openLeadModal */

use app\modules\billing\widgets\BalanceWidget;
use app\modules\partner\widgets\ArticleWidget;
use app\modules\insurance_companies\models\InsuranceCompany;
use app\modules\cars\models\CarMark;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Лиды';
$this->params['breadcrumbs'][] = $this->title;

$cityList = \app\models\City::getList();
$statusList = \app\models\LeadStatus::getList();
$insuranceList = ArrayHelper::map(InsuranceCompany::find()->orderBy('full_name')->all(), 'id', 'full_name');
$carMarkList = CarMark::getList();
$openLeadModal = $openLeadModal ?? false;
$carModelsUrl = \yii\helpers\Url::to(['/partner/default/car-models']);
$searchClientUrl = \yii\helpers\Url::to(['/partner/default/search-client']);
?>
<div class="partner-default-leads">
    <div class="row">
        <div class="col-12">
            <h1>Здравствуйте, <?= Yii::$app->user->identity->username?> 🖐</h1>
        </div>
        <div class="col-12 mt-5">
            <?=  BalanceWidget::widget([
                'userId' => Yii::$app->user->identity->id,
                'walletUrl' => ['/billing/billing/index'],
            ]);?>
        </div>
        <div class="col-12 mt-5">
            <div class="card card-transparent r-16">
                <div class="card-body p-5">
                    <div class="row flex-column justify-content-center align-items-center">
                        <div class="col-auto mt-4">
                            <h3>Вы ещё не создали ни одной заявки 😎</h3>
                        </div>
                        <div class="col-auto">Создайте первую заявку</div>
                        <div class="col-auto mt-4">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#leadModal">
                                Создать заявку
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="col-12 mt-5">
            <?= ArticleWidget::widget([]); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="leadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="leadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content r-16">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="leadModalLabel">Создание заявки</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">

                    <?php $form = ActiveForm::begin([
                        'id' => 'lead-form',
                        'action' => ['/partner/default/create-lead'],
                        'method' => 'post',
                        'fieldConfig' => [
                            'template' => "{label}\n{input}\n{error}",
                            'errorOptions' => ['class' => 'invalid-feedback'],
                        ],
                    ]); ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-shadow card-grey r-16">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h3>Информация о ДТП</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <?= $form->field($leadForm, 'dtp_date')
                                                ->textInput(['type' => 'date', 'placeholder' => 'Дата совершения ДТП'])
                                                ->label(false)?>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <?= $form->field($leadForm, 'dtp_time')
                                                ->textInput(['type' => 'time', 'placeholder' => 'Время совершения ДТП'])
                                                ->label(false)?>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <?= $form->field($leadForm, 'city_id')->dropDownList($cityList, ['prompt' => 'Город совершения ДТП'])->label(false) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <?= $form->field($leadForm, 'insurance_company_id')->dropDownList($insuranceList, ['prompt' => 'Страховая компания потерпевшего']) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-3">
                                            <?= $form->field($leadForm, 'car_mark_id')->dropDownList($carMarkList, [
                                                'prompt' => 'Марка автомобиля',
                                                'id' => 'leadform-car_mark_id',
                                            ]) ?>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <?= $form->field($leadForm, 'car_model_id')->dropDownList([], [
                                                'prompt' => 'Модель автомобиля',
                                                'id' => 'leadform-car_model_id',
                                                'disabled' => true,
                                            ]) ?>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <?= $form->field($leadForm, 'car_number')->textInput([
                                                'maxlength' => true,
                                                'placeholder' => 'А123БВ77',
                                                'id' => 'leadform-car_number',
                                                'autocomplete' => 'off',
                                            ]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="card card-shadow card-grey r-16">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h3>Контакты клиента</h3>
                                        </div>
                                    </div>
                                    <?= Html::activeHiddenInput($leadForm, 'client_id', ['id' => 'leadform-client_id']) ?>
                                    <div class="row">
                                        <div class="col-12 col-md-6 client-autocomplete-wrap">
                                            <?= $form->field($leadForm, 'f')->textInput([
                                                'placeholder' => 'Фамилия',
                                                'id' => 'leadform-f',
                                                'autocomplete' => 'off',
                                            ])->label(false) ?>
                                            <div class="client-autocomplete-list" data-for="f" style="display:none;"></div>
                                        </div>
                                        <div class="col-12 col-md-6 client-autocomplete-wrap">
                                            <?= $form->field($leadForm, 'i')->textInput([
                                                'placeholder' => 'Имя',
                                                'id' => 'leadform-i',
                                                'autocomplete' => 'off',
                                            ])->label(false) ?>
                                            <div class="client-autocomplete-list" data-for="i" style="display:none;"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <?= $form->field($leadForm, 'o')->textInput([
                                                'placeholder' => 'Отчество',
                                                'id' => 'leadform-o',
                                                'autocomplete' => 'off',
                                            ])->label(false) ?>
                                        </div>
                                        <div class="col-12 col-md-6 client-autocomplete-wrap">
                                            <?= $form->field($leadForm, 'tel')->textInput([
                                                'placeholder' => '+7 (999) 999-99-99',
                                                'id' => 'leadform-tel',
                                                'autocomplete' => 'off',
                                            ])->label(false) ?>
                                            <div class="client-autocomplete-list" data-for="tel" style="display:none;"></div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($leadForm, 'status_id')->dropDownList($statusList, ['prompt' => 'Выберите статус']) ?>
                        </div>
                    </div>
                    <?= $form->field($leadForm, 'report')->textarea(['rows' => 4, 'placeholder' => 'Отчёт партнёра']) ?>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

<?php
$this->registerJs(<<<JS
(function(){
    var markSelect = document.getElementById('leadform-car_mark_id');
    var modelSelect = document.getElementById('leadform-car_model_id');
    var carModelsUrl = 
JS
. \yii\helpers\Json::encode($carModelsUrl) . <<<JS
;
    if (!markSelect || !modelSelect) return;
    var promptText = modelSelect.options[0] ? modelSelect.options[0].text : 'Модель автомобиля';
    function updateCarModels(){
        var markId = markSelect.value;
        modelSelect.innerHTML = '';
        var opt = document.createElement('option');
        opt.value = '';
        opt.textContent = promptText;
        modelSelect.appendChild(opt);
        modelSelect.disabled = true;
        if (!markId) return;
        var url = carModelsUrl + (carModelsUrl.indexOf('?') >= 0 ? '&' : '?') + 'mark_id=' + encodeURIComponent(markId);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r){ return r.json(); })
            .then(function(data){
                for (var id in data) {
                    if (!data.hasOwnProperty(id)) continue;
                    var o = document.createElement('option');
                    o.value = id;
                    o.textContent = data[id];
                    modelSelect.appendChild(o);
                }
                modelSelect.disabled = false;
            })
            .catch(function(){ modelSelect.disabled = false; });
    }
    markSelect.addEventListener('change', updateCarModels);
    updateCarModels();
})();
JS
, \yii\web\View::POS_READY);
if ($openLeadModal) {
    $this->registerJs(<<<JS
        (function(){
            var el = document.getElementById('leadModal');
            if (el) {
                new bootstrap.Modal(el).show();
            }
        })();
    JS
    );
}

$this->registerJs(<<<'JS'
(function(){
    var el = document.getElementById('leadform-car_number');
    if (!el) return;
    var reLetter = /[А-Яа-я]/;
    var reDigit = /\d/;
    function isValidCarNumber(val) {
        if (val.length === 0) return true;
        if (val.length === 1) return reLetter.test(val);
        if (val.length <= 4) return reLetter.test(val[0]) && /^\d+$/.test(val.slice(1));
        if (val.length <= 6) return reLetter.test(val[0]) && /^\d{3}$/.test(val.slice(1, 4)) && reLetter.test(val[4]) && (val.length === 5 ? reLetter.test(val[5]) : reLetter.test(val[5]));
        if (val.length <= 9) return reLetter.test(val[0]) && /^\d{3}$/.test(val.slice(1, 4)) && reLetter.test(val[4]) && reLetter.test(val[5]) && /^\d{2,3}$/.test(val.slice(6));
        return false;
    }
    function formatCarNumber(val) {
        var s = val.toUpperCase().replace(/\s/g, '');
        var out = '';
        for (var i = 0; i < s.length && out.length < 9; i++) {
            var c = s[i];
            var pos = out.length;
            if (pos === 0 && reLetter.test(c)) out += c;
            else if (pos >= 1 && pos <= 3 && reDigit.test(c)) out += c;
            else if (pos >= 4 && pos <= 5 && reLetter.test(c)) out += c;
            else if (pos >= 6 && pos <= 8 && reDigit.test(c)) out += c;
        }
        return out;
    }
    el.addEventListener('input', function(){
        var start = this.selectionStart;
        var val = formatCarNumber(this.value);
        this.value = val;
        this.setSelectionRange(Math.min(start, val.length), Math.min(start, val.length));
    });
    el.addEventListener('paste', function(e){
        e.preventDefault();
        var text = (e.clipboardData || window.clipboardData).getData('text');
        this.value = formatCarNumber(text);
    });
})();
JS
, \yii\web\View::POS_READY);

$this->registerCss(<<<CSS
.client-autocomplete-wrap { position: relative; }
.client-autocomplete-list {
    position: absolute;
    left: 0;
    right: 0;
    top: 100%;
    z-index: 1050;
    background: #fff;
    border: 1px solid #dee2e6;
    border-top: none;
    max-height: 220px;
    overflow-y: auto;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
}
.client-autocomplete-list .item {
    padding: 8px 12px;
    cursor: pointer;
    white-space: pre-line;
    font-size: 14px;
    border-bottom: 1px solid #eee;
}
.client-autocomplete-list .item:hover { background: #f8f9fa; }
.client-autocomplete-list .item:last-child { border-bottom: none; }
CSS
);

$this->registerJs('var _searchClientUrl = ' . \yii\helpers\Json::encode($searchClientUrl) . ';', \yii\web\View::POS_HEAD);
$this->registerJs(<<<'JS'
(function(){
    var url = typeof _searchClientUrl !== 'undefined' ? _searchClientUrl : '';
    var clientIdEl = document.getElementById('leadform-client_id');
    var fEl = document.getElementById('leadform-f');
    var iEl = document.getElementById('leadform-i');
    var oEl = document.getElementById('leadform-o');
    var telEl = document.getElementById('leadform-tel');
    if (!clientIdEl || !fEl || !iEl || !oEl || !telEl) return;
    var lists = document.querySelectorAll('.client-autocomplete-list');
    var debounceTimer;
    function hideAll() { lists.forEach(function(l){ l.style.display = 'none'; l.innerHTML = ''; }); }
    var skipClearClientId = false;
    function clearClientId(e) { if (skipClearClientId || (e && e.isTrusted === false)) return; clientIdEl.value = ''; }
    fEl.addEventListener('input', function(e){ clearClientId(e); });
    iEl.addEventListener('input', function(e){ clearClientId(e); });
    oEl.addEventListener('input', function(e){ clearClientId(e); });
    telEl.addEventListener('input', function(e){ clearClientId(e); });
    function showList(forId, items) {
        hideAll();
        var list = document.querySelector('.client-autocomplete-list[data-for="' + forId + '"]');
        if (!list || !items.length) return;
        list.innerHTML = '';
        items.forEach(function(it) {
            var div = document.createElement('div');
            div.className = 'item';
            div.textContent = it.label;
            div.dataset.id = it.id;
            div.dataset.f = it.f || '';
            div.dataset.i = it.i || '';
            div.dataset.o = it.o || '';
            div.dataset.tel = it.tel || '';
            div.addEventListener('click', function() {
                skipClearClientId = true;
                fEl.value = this.dataset.f;
                iEl.value = this.dataset.i;
                oEl.value = this.dataset.o;
                telEl.value = this.dataset.tel;
                clientIdEl.value = this.dataset.id;
                hideAll();
                setTimeout(function(){ skipClearClientId = false; }, 0);
            });
            list.appendChild(div);
        });
        list.style.display = 'block';
    }
    function onInput(forId, value) {
        value = value.trim();
        if (value.length < 2) { hideAll(); return; }
        var u = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'q=' + encodeURIComponent(value);
        fetch(u, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r){ return r.json(); })
            .then(function(data){ showList(forId, data); })
            .catch(function(){ hideAll(); });
    }
    function attach(fieldId) {
        var el = document.getElementById('leadform-' + fieldId);
        if (!el) return;
        el.addEventListener('input', function() {
            clearClientId(this);
            clearTimeout(debounceTimer);
            var self = this, v = this.value, forId = fieldId;
            debounceTimer = setTimeout(function(){ onInput(forId, v); }, 250);
        });
        el.addEventListener('blur', function() { setTimeout(hideAll, 200); });
    }
    attach('f');
    attach('i');
    attach('tel');
})();
JS
, \yii\web\View::POS_READY);
