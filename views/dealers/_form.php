<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\City;

/** @var yii\web\View $this */
/** @var app\models\Dealers $model */
/** @var yii\widgets\ActiveForm $form */

// Получаем текущий город для отображения
$currentCityName = '';
if ($model->city_id) {
    $city = City::findOne($model->city_id);
    if ($city) {
        $currentCityName = $city->name;
    }
}
?>
<div class="dealers-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group field-dealers-city_id">
        <label class="control-label" for="dealers-city_id">Город</label>
        <input type="text" 
               id="city-autocomplete" 
               class="form-control" 
               value="<?= Html::encode($currentCityName) ?>" 
               placeholder="Начните вводить название города..."
               autocomplete="off">
        <input type="hidden" 
               id="dealers-city_id" 
               name="Dealers[city_id]" 
               value="<?= $model->city_id ?>">
        <div id="city-suggestions" class="list-group" style="position: absolute; z-index: 1000; max-height: 200px; overflow-y: auto; display: none; width: 100%; margin-top: 2px; border: 1px solid #ced4da; border-radius: 0.25rem; background-color: #fff;"></div>
        <div class="help-block"></div>
    </div>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Подключаем inputmask через CDN (более надежный способ)
$this->registerJsFile(
    'https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/jquery.inputmask.min.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

$baseUrl = Url::to(['dealers/search-cities']);
$js = <<<JS
(function() {
    var cityInput = document.getElementById('city-autocomplete');
    var cityIdInput = document.getElementById('dealers-city_id');
    var suggestionsDiv = document.getElementById('city-suggestions');
    var searchTimeout;
    var formGroup = cityInput.closest('.form-group');
    
    // Позиционируем suggestions относительно input
    formGroup.style.position = 'relative';
    
    cityInput.addEventListener('input', function() {
        var query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            suggestionsDiv.style.display = 'none';
            cityIdInput.value = '';
            return;
        }
        
        searchTimeout = setTimeout(function() {
            var url = '$baseUrl';
            var separator = url.indexOf('?') >= 0 ? '&' : '?';
            fetch(url + separator + 'q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    suggestionsDiv.innerHTML = '';
                    
                    if (data.length === 0) {
                        suggestionsDiv.style.display = 'none';
                        return;
                    }
                    
                    data.forEach(function(city) {
                        var item = document.createElement('a');
                        item.href = '#';
                        item.className = 'list-group-item list-group-item-action';
                        item.textContent = city.text;
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            cityInput.value = city.text;
                            cityIdInput.value = city.id;
                            suggestionsDiv.style.display = 'none';
                        });
                        suggestionsDiv.appendChild(item);
                    });
                    
                    suggestionsDiv.style.display = 'block';
                })
                .catch(function(error) {
                    console.error('Error:', error);
                });
        }, 300);
    });
    
    // Скрываем подсказки при клике вне поля
    document.addEventListener('click', function(e) {
        if (!formGroup.contains(e.target)) {
            suggestionsDiv.style.display = 'none';
        }
    });
    
    // Очищаем city_id если поле очищено
    cityInput.addEventListener('blur', function() {
        if (this.value === '') {
            cityIdInput.value = '';
        }
    });
})();

// Применяем маску для телефона
jQuery(document).ready(function($) {
    $('#dealers-phone').inputmask({
        mask: '+7 (999) 999-99-99',
        placeholder: '+7 (___) ___-__-__',
        showMaskOnHover: false,
        showMaskOnFocus: true,
        clearIncomplete: true
    });
});
JS;

$this->registerJs($js);
?>
