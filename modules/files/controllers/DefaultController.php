<?php

namespace app\modules\files\controllers;

use app\controllers\AccessController;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Загрузка файлов (для виджета Upload).
 */
class DefaultController extends AccessController
{
    /**
     * Принимает загруженные файлы, сохраняет в web/uploads, возвращает JSON.
     * Ограничения берутся из модуля: maxFiles, maxFileSizeMb.
     */
    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        if (!$request->isPost) {
            return ['success' => false, 'error' => 'Метод не разрешён'];
        }

        $files = UploadedFile::getInstancesByName('files');
        if (empty($files)) {
            $single = UploadedFile::getInstanceByName('file');
            if ($single) {
                $files = [$single];
            }
        }

        if (empty($files)) {
            return ['success' => false, 'error' => 'Нет файлов'];
        }

        $maxFiles = (int) ($this->module->maxFiles ?? 20);
        $maxFileSizeBytes = (int) (($this->module->maxFileSizeMb ?? 20) * 1024 * 1024);

        if (count($files) > $maxFiles) {
            return ['success' => false, 'error' => 'Превышено максимальное количество файлов: ' . $maxFiles];
        }

        $basePath = Yii::getAlias($this->module->uploadPath);
        if (!is_dir($basePath)) {
            mkdir($basePath, 0755, true);
        }
        $subDir = date('Y-m-d');
        $fullDir = $basePath . DIRECTORY_SEPARATOR . $subDir;
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0755, true);
        }

        $baseUrl = rtrim(Yii::getAlias($this->module->uploadUrl), '/');
        $saved = [];

        foreach ($files as $file) {
            if ($file->size > $maxFileSizeBytes) {
                continue;
            }
            $name = $file->name;
            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
            $safeName = time() . '_' . $safeName;
            $path = $fullDir . DIRECTORY_SEPARATOR . $safeName;
            if ($file->saveAs($path)) {
                $relativePath = $subDir . '/' . $safeName;
                $saved[] = [
                    'name' => $name,
                    'path' => $relativePath,
                    'url' => $baseUrl . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $relativePath),
                ];
            }
        }

        return ['success' => true, 'files' => $saved];
    }
}
