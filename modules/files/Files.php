<?php

namespace app\modules\files;

/**
 * Модуль загрузки файлов.
 */
class Files extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\files\controllers';

    /** @var string Путь к папке загрузок относительно @webroot (по умолчанию web/uploads) */
    public $uploadPath = '@webroot/uploads';

    /** @var string URL папки загрузок для доступа по HTTP */
    public $uploadUrl = '@web/uploads';
}
