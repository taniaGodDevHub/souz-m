<?php
/**
 * @var string $id
 * @var string $title
 * @var string $fileTypes
 * @var string $accept
 * @var string $mode
 * @var int $maxFiles
 * @var int $maxFileSizeMb
 * @var string $uploadUrl
 * @var string $name
 * @var int $slotCount
 * @var bool $isImage
 * @var int $maxFileSizeBytes
 */

use yii\helpers\Html;

$showMulti = ($mode === 'multi' || $mode === 'both');
$showItems = ($mode === 'items' || $mode === 'both');
$headerText = $fileTypes !== '' ? $title . ' (' . $fileTypes . ')' : $title;
?>
<div class="files-upload-widget" id="<?= Html::encode($id) ?>" data-upload-url="<?= Html::encode($uploadUrl) ?>" data-accept="<?= Html::encode($accept) ?>" data-max-files="<?= (int) $maxFiles ?>" data-max-bytes="<?= (int) $maxFileSizeBytes ?>" data-name="<?= Html::encode($name) ?>" data-is-image="<?= $isImage ? '1' : '0' ?>" data-mode="<?= Html::encode($mode) ?>">
    <div class="files-upload-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="files-upload-title mb-0"><?= Html::encode($headerText) ?></div>
        <?php if ($showMulti): ?>
            <button type="button" class="btn btn-secondary files-upload-multi-btn">
                <img src="<?= Yii::getAlias('@web/img/download.svg')?>" alt="">
                    Мультизагрузка
            </button>
        <?php endif; ?>
    </div>
    <div class="files-upload-grid row mt-3">
        <?php for ($i = 0; $i < $slotCount; $i++): ?>
            <div class="files-upload-slot col-4 col-md-2 mb-3" data-slot="<?= $i ?>">
                <div class="files-upload-slot-inner files-upload-slot-empty bg-white r-16">
                    <?php if ($isImage): ?>
                        <span class="files-upload-icon files-upload-icon-camera">
                            <img src="<?= Yii::getAlias('@web/img/photo-plus.svg')?>" alt="">
                        </span>
                    <?php else: ?>
                        <span class="files-upload-icon files-upload-icon-plus">
                            <img src="<?= Yii::getAlias('@web/img/plus.svg')?>" alt="">
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endfor; ?>
    </div>
    <input type="file" class="files-upload-input-single d-none" accept="<?= Html::encode($accept) ?>" tabindex="-1">
    <input type="file" class="files-upload-input-multi d-none" accept="<?= Html::encode($accept) ?>" multiple tabindex="-1">
    <div class="files-upload-hidden-container"></div>
    <p class="files-upload-restrictions text-danger small mt-2 mb-0">Ограничения по размеру файла и количеству: до <?= $maxFiles ?> файлов, не более <?= $maxFileSizeMb ?> МБ каждый.</p>
</div>
<?php
$widgetId = $id;
$css = <<<CSS
.files-upload-multi-btn { display: inline-flex; align-items: center; gap: 0.35rem; border-radius: 1.5rem; }
.files-upload-grid {}
@media (min-width: 768px) { .files-upload-grid {  } }
.files-upload-slot {}
.files-upload-slot-inner { width: 100%; height: 100%; border: 1px solid #dee2e6; border-radius: 8px; background: #e9ecef; display: flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; }
.files-upload-slot-inner:hover { background: #dee2e6; }
.files-upload-slot-empty .files-upload-icon { color: #495057; font-size: 1.5rem; padding-bottom: 3rem; padding-top: 3rem; }
.files-upload-slot-inner img { width: 100%; height: 100%; object-fit: cover; }
.files-upload-hidden-container { display: none; }
CSS;
$this->registerCss($css);

$widgetIdJs = json_encode($id);
$this->registerJs(<<<JS
(function(){
    var w = document.getElementById($widgetIdJs);
    if (!w) return;
    var uploadUrl = w.dataset.uploadUrl;
    var accept = w.dataset.accept || '';
    var maxFiles = parseInt(w.dataset.maxFiles, 10) || 20;
    var maxBytes = parseInt(w.dataset.maxBytes, 10) || 20971520;
    var name = w.dataset.name || 'files';
    var isImage = w.dataset.isImage === '1';
    var mode = w.dataset.mode || 'both';
    var singleInput = w.querySelector('.files-upload-input-single');
    var multiInput = w.querySelector('.files-upload-input-multi');
    var hiddenContainer = w.querySelector('.files-upload-hidden-container');
    var slots = w.querySelectorAll('.files-upload-slot');
    var multiBtn = w.querySelector('.files-upload-multi-btn');
    var uploaded = [];
    function renderHiddenInputs() {
        hiddenContainer.innerHTML = '';
        uploaded.forEach(function(f, i) {
            var inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = name + '[' + i + '][path]';
            inp.value = f.path;
            hiddenContainer.appendChild(inp);
            var inpName = document.createElement('input');
            inpName.type = 'hidden';
            inpName.name = name + '[' + i + '][name]';
            inpName.value = f.name;
            hiddenContainer.appendChild(inpName);
        });
    }
    function updateSlots() {
        slots.forEach(function(slot, idx) {
            var inner = slot.querySelector('.files-upload-slot-inner');
            var item = uploaded[idx];
            inner.classList.remove('files-upload-slot-empty');
            inner.innerHTML = '';
            if (item) {
                if (isImage && (item.url || item.path)) {
                    var img = document.createElement('img');
                    img.src = item.url || (window.location.origin + '/uploads/' + item.path);
                    img.alt = item.name;
                    inner.appendChild(img);
                } else {
                    inner.textContent = item.name || 'Файл';
                    inner.style.fontSize = '0.75rem';
                    inner.style.padding = '4px';
                    inner.style.wordBreak = 'break-all';
                }
                inner.style.cursor = 'default';
            } else {
                inner.classList.add('files-upload-slot-empty');
                inner.innerHTML = isImage ? `<span class="files-upload-icon files-upload-icon-camera"><svg width="23" height="21" viewBox="0 0 23 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M17.75 5C19.5449 5 21 6.45507 21 8.25V9.5C21 10.6046 20.1046 11.5 19 11.5H18.25C17.6028 11.5 17.0709 11.9918 17.0068 12.6221L16.9932 12.8779C16.9291 13.5082 16.3972 14 15.75 14H15.5C14.6716 14 14 14.6716 14 15.5V16.5C14 17.8807 12.8807 19 11.5 19H5C2.79086 19 1 17.2091 1 15V9C1 6.79086 2.79086 5 5 5H17.75ZM11 9C9.34315 9 8 10.3431 8 12C8 13.6569 9.34315 15 11 15C12.6569 15 14 13.6569 14 12C14 10.3431 12.6569 9 11 9ZM12.9297 1C13.5984 1.00002 14.2228 1.33424 14.5938 1.89062L15.667 3.5H6.33301L7.40625 1.89062C7.77717 1.33424 8.40163 1.00002 9.07031 1H12.9297Z" fill="#28303F"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M18.8906 12.3107C19.3048 12.3107 19.6406 12.6464 19.6406 13.0607L19.6406 15.1391L21.7191 15.1391C22.1333 15.1391 22.4691 15.4749 22.4691 15.8891C22.4691 16.3033 22.1333 16.6391 21.7191 16.6391L19.6406 16.6391V18.7175C19.6406 19.1317 19.3048 19.4675 18.8906 19.4675C18.4764 19.4675 18.1406 19.1317 18.1406 18.7175V16.6391L16.0622 16.6391C15.648 16.6391 15.3122 16.3033 15.3122 15.8891C15.3122 15.4749 15.648 15.1391 16.0622 15.1391L18.1406 15.1391L18.1406 13.0607C18.1406 12.6464 18.4764 12.3107 18.8906 12.3107Z" fill="#28303F"/>
</svg>
</span>` : `<span class="files-upload-icon files-upload-icon-plus"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4 12H20M12 4V20" stroke="#6E6B7C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
</span>`;
                inner.style.cursor = 'pointer';
            }
        });
        renderHiddenInputs();
    }
    function doUpload(files, cb) {
        if (uploaded.length + files.length > maxFiles) { files = Array.from(files).slice(0, maxFiles - uploaded.length); }
        if (files.length === 0) { if (cb) cb(); return; }
        var fd = new FormData();
        var csrfParam = document.querySelector('meta[name="csrf-param"]');
        var csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfParam && csrfToken) {
            fd.append(csrfParam.getAttribute('content'), csrfToken.getAttribute('content'));
        }
        for (var i = 0; i < files.length; i++) {
            if (files[i].size > maxBytes) continue;
            fd.append('files[]', files[i]);
        }
        fetch(uploadUrl, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success && data.files) {
                    data.files.forEach(function(f) { uploaded.push(f); });
                }
                if (cb) cb();
                updateSlots();
            })
            .catch(function() { if (cb) cb(); updateSlots(); });
    }
    if (mode === 'items' || mode === 'both') {
        slots.forEach(function(slot) {
            slot.addEventListener('click', function() {
                var inner = slot.querySelector('.files-upload-slot-inner');
                if (inner && inner.classList.contains('files-upload-slot-empty')) {
                    singleInput.value = '';
                    singleInput.click();
                }
            });
        });
    }
    singleInput.addEventListener('change', function() {
        if (this.files.length) { doUpload(this.files); }
        this.value = '';
    });
    if (multiBtn) {
        multiBtn.addEventListener('click', function() { multiInput.value = ''; multiInput.click(); });
    }
    multiInput.addEventListener('change', function() {
        if (this.files.length) { doUpload(this.files); }
        this.value = '';
    });
    updateSlots();
})();
JS
, \yii\web\View::POS_READY);
