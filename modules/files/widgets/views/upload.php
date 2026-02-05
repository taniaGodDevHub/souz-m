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
<?php $imgBase = rtrim(Yii::getAlias('@web/img'), '/') . '/'; ?>
<div class="files-upload-widget" id="<?= Html::encode($id) ?>" data-upload-url="<?= Html::encode($uploadUrl) ?>" data-accept="<?= Html::encode($accept) ?>" data-max-files="<?= (int) $maxFiles ?>" data-max-bytes="<?= (int) $maxFileSizeBytes ?>" data-name="<?= Html::encode($name) ?>" data-is-image="<?= $isImage ? '1' : '0' ?>" data-mode="<?= Html::encode($mode) ?>" data-img-base="<?= Html::encode($imgBase) ?>">
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
    var imgBase = w.dataset.imgBase || '';
    var singleInput = w.querySelector('.files-upload-input-single');
    var multiInput = w.querySelector('.files-upload-input-multi');
    var hiddenContainer = w.querySelector('.files-upload-hidden-container');
    var grid = w.querySelector('.files-upload-grid');
    var multiBtn = w.querySelector('.files-upload-multi-btn');
    var uploaded = [];
    function getSlots() { return w.querySelectorAll('.files-upload-slot'); }
    function getEmptySlotInnerHtml() {
        return isImage ? '<span class="files-upload-icon files-upload-icon-camera"><img src="' + imgBase + 'photo-plus.svg" alt=""></span>' : '<span class="files-upload-icon files-upload-icon-plus"><img src="' + imgBase + 'plus.svg" alt=""></span>';
    }
    function createEmptySlot() {
        var slot = document.createElement('div');
        slot.className = 'files-upload-slot col-4 col-md-2 mb-3';
        var inner = document.createElement('div');
        inner.className = 'files-upload-slot-inner files-upload-slot-empty bg-white r-16';
        inner.innerHTML = getEmptySlotInnerHtml();
        slot.appendChild(inner);
        return slot;
    }
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
        var slots = getSlots();
        while (slots.length < uploaded.length) {
            grid.appendChild(createEmptySlot());
            slots = getSlots();
        }
        slots.forEach(function(slot, idx) {
            var inner = slot.querySelector('.files-upload-slot-inner');
            if (!inner) return;
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
                inner.innerHTML = getEmptySlotInnerHtml();
                inner.style.cursor = 'pointer';
            }
        });
        if (uploaded.length < maxFiles && getSlots().length <= uploaded.length) {
            grid.appendChild(createEmptySlot());
        }
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
    if (grid && (mode === 'items' || mode === 'both')) {
        grid.addEventListener('click', function(e) {
            var slot = e.target.closest('.files-upload-slot');
            if (!slot) return;
            var inner = slot.querySelector('.files-upload-slot-inner');
            if (inner && inner.classList.contains('files-upload-slot-empty')) {
                singleInput.value = '';
                singleInput.click();
            }
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
