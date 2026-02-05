<?php

namespace app\modules\files\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Виджет загрузки файлов.
 *
 * Параметры:
 * - title: заголовок (например "Фотографии с места ДТП")
 * - fileTypes: строка типа файлов для отображения (например "jpg/png" или "PDF")
 * - accept: атрибут accept для input (например ".jpg,.jpeg,.png" или "application/pdf")
 * - mode: 'items' | 'multi' | 'both' — иконки слотов, кнопка мультизагрузки или оба варианта
 * - maxFiles: максимум файлов (по умолчанию 20)
 * - maxFileSizeMb: максимум размер одного файла в МБ (по умолчанию 20)
 * - uploadUrl: URL экшена загрузки
 * - name: имя для скрытых полей (массив путей загруженных файлов)
 * - slotCount: количество слотов-клеток (по умолчанию 10)
 * - isImage: true для превью картинок (камера+), false для PDF/других (плюс)
 *
 * Использование:
 * ```php
 * echo UploadWidget::widget([
 *     'title' => 'Фотографии с места ДТП (jpg/png)',
 *     'fileTypes' => 'jpg/png',
 *     'accept' => '.jpg,.jpeg,.png',
 *     'mode' => 'both',
 *     'name' => 'photos',
 *     'isImage' => true,
 * ]);
 * ```
 */
class UploadWidget extends Widget
{
    /** @var string Заголовок блока */
    public $title = '';
    /** @var string Тип файлов для отображения (например "jpg/png") */
    public $fileTypes = '';
    /** @var string Атрибут accept для input */
    public $accept = '';
    /** @var string 'items' | 'multi' | 'both' */
    public $mode = 'both';
    /** @var int Максимум файлов */
    public $maxFiles = 20;
    /** @var int Максимум размер одного файла в МБ */
    public $maxFileSizeMb = 20;
    /** @var array|string URL загрузки */
    public $uploadUrl;
    /** @var string Имя для скрытых полей (передаётся массив path) */
    public $name = 'files';
    /** @var int Количество слотов в сетке */
    public $slotCount = 10;
    /** @var bool true — превью как картинки, иконка камера+; false — иконка плюс */
    public $isImage = true;

    public function init()
    {
        parent::init();
        if ($this->uploadUrl === null) {
            $this->uploadUrl = Url::to(['/files/default/upload']);
        }
        if (is_array($this->uploadUrl)) {
            $this->uploadUrl = Url::to($this->uploadUrl);
        }
    }

    public function run()
    {
        $id = $this->getId();
        $maxFileSizeBytes = $this->maxFileSizeMb * 1024 * 1024;

        return $this->render('upload', [
            'id' => $id,
            'title' => $this->title,
            'fileTypes' => $this->fileTypes,
            'accept' => $this->accept,
            'mode' => $this->mode,
            'maxFiles' => $this->maxFiles,
            'maxFileSizeMb' => $this->maxFileSizeMb,
            'uploadUrl' => $this->uploadUrl,
            'name' => $this->name,
            'slotCount' => $this->slotCount,
            'isImage' => $this->isImage,
            'maxFileSizeBytes' => $maxFileSizeBytes,
        ]);
    }
}
