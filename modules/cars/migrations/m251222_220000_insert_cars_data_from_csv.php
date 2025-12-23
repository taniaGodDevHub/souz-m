<?php

use yii\db\Migration;

/**
 * Миграция для заполнения таблиц марок и моделей автомобилей из CSV файла
 */
class m251222_220000_insert_cars_data_from_csv extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $csvFile = Yii::getAlias('@app/cars.csv');
        
        if (!file_exists($csvFile)) {
            throw new \Exception("CSV файл не найден: {$csvFile}");
        }

        $handle = fopen($csvFile, 'r');
        if ($handle === false) {
            throw new \Exception("Не удалось открыть CSV файл");
        }

        // Пропускаем заголовок
        $header = fgetcsv($handle);
        
        $marksMap = []; // Маппинг названия марки -> ID в БД
        $marksData = []; // Данные марок для batch insert
        $modelsData = []; // Данные моделей для batch insert
        $processed = 0;
        $currentTime = time();
        $lineNumber = 1; // Для отладки

        echo "Начало обработки CSV файла...\n";

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;
            
            if (count($row) < 13) {
                echo "Пропущена строка {$lineNumber}: недостаточно колонок (" . count($row) . ")\n";
                continue; // Пропускаем некорректные строки
            }

            // Структура CSV: ID_MARK, Марка, Марка кириллица, Популярная марка, Страна, Год марки от, Год марки до, MODEL_ID, Модель, Модель кириллица, Класс, Год модели от, Год модели до
            // Пропускаем первый столбец (ID_MARK, индекс 0) и MODEL_ID (индекс 7)
            $markName = isset($row[1]) ? trim($row[1]) : '';
            $markNameCyrillic = isset($row[2]) ? trim($row[2]) : '';
            $isPopular = isset($row[3]) && !empty(trim($row[3])) ? (int)trim($row[3]) : 0;
            $country = isset($row[4]) ? trim($row[4]) : '';
            $markYearFrom = isset($row[5]) && !empty(trim($row[5])) ? (int)trim($row[5]) : null;
            $markYearTo = isset($row[6]) && !empty(trim($row[6])) ? (int)trim($row[6]) : null;
            
            $modelName = isset($row[8]) ? trim($row[8]) : '';
            $modelNameCyrillic = isset($row[9]) ? trim($row[9]) : '';
            $class = isset($row[10]) && !empty(trim($row[10])) ? trim($row[10]) : null;
            $modelYearFrom = isset($row[11]) && !empty(trim($row[11])) ? (int)trim($row[11]) : null;
            $modelYearTo = isset($row[12]) && !empty(trim($row[12])) ? (int)trim($row[12]) : null;

            // Проверяем, существует ли марка
            if (empty($markName)) {
                continue;
            }

            // Сохраняем данные марки (если еще не сохранена)
            if (!isset($marksMap[$markName])) {
                $marksData[] = [
                    'name' => $markName,
                    'name_cyrillic' => !empty($markNameCyrillic) ? $markNameCyrillic : null,
                    'is_popular' => $isPopular,
                    'country' => !empty($country) ? $country : null,
                    'year_from' => $markYearFrom,
                    'year_to' => $markYearTo,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
                $marksMap[$markName] = count($marksData); // Временный индекс, потом заменим на реальный ID
            }

            // Сохраняем данные модели
            if (!empty($modelName)) {
                $modelsData[] = [
                    'mark_name' => $markName, // Временная ссылка на название марки
                    'name' => $modelName,
                    'name_cyrillic' => !empty($modelNameCyrillic) ? $modelNameCyrillic : null,
                    'class' => $class,
                    'year_from' => $modelYearFrom,
                    'year_to' => $modelYearTo,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
                $processed++;
            }
        }

        fclose($handle);

        echo "Прочитано строк из CSV: {$lineNumber}\n";
        echo "Найдено уникальных марок: " . count($marksData) . "\n";
        echo "Найдено моделей: {$processed}\n";
        echo "Начало вставки данных в БД...\n";

        // Вставляем марки батчами
        if (!empty($marksData)) {
            // Удаляем дубликаты марок по названию
            $uniqueMarks = [];
            foreach ($marksData as $mark) {
                $uniqueMarks[$mark['name']] = $mark;
            }
            
            echo "Уникальных марок после дедупликации: " . count($uniqueMarks) . "\n";
            
            // Вставляем уникальные марки
            $insertedMarks = 0;
            $existingMarks = 0;
            foreach ($uniqueMarks as $markName => $markData) {
                // Проверяем, не существует ли уже марка
                $existingMark = (new \yii\db\Query())
                    ->select('id')
                    ->from('{{%car_mark}}')
                    ->where(['name' => $markName])
                    ->one($this->db);
                
                if (!$existingMark) {
                    $this->insert('{{%car_mark}}', $markData);
                    $markId = (int)$this->db->getLastInsertID();
                    $insertedMarks++;
                } else {
                    $markId = (int)$existingMark['id'];
                    $existingMarks++;
                }
                
                $marksMap[$markName] = $markId;
            }
            
            echo "Вставлено новых марок: {$insertedMarks}\n";
            echo "Найдено существующих марок: {$existingMarks}\n";
        }

        // Вставляем модели батчами
        if (!empty($modelsData)) {
            $batchSize = 100;
            $batches = array_chunk($modelsData, $batchSize);
            $totalBatches = count($batches);
            $insertedModels = 0;
            
            echo "Вставка моделей батчами (размер батча: {$batchSize}, всего батчей: {$totalBatches})...\n";
            
            foreach ($batches as $batchIndex => $batch) {
                $insertData = [];
                foreach ($batch as $model) {
                    if (isset($marksMap[$model['mark_name']])) {
                        $insertData[] = [
                            $marksMap[$model['mark_name']],
                            $model['name'],
                            $model['name_cyrillic'],
                            $model['class'],
                            $model['year_from'],
                            $model['year_to'],
                            $model['created_at'],
                            $model['updated_at'],
                        ];
                    }
                }
                
                if (!empty($insertData)) {
                    $this->batchInsert('{{%car_model}}', 
                        ['mark_id', 'name', 'name_cyrillic', 'class', 'year_from', 'year_to', 'created_at', 'updated_at'],
                        $insertData
                    );
                    $insertedModels += count($insertData);
                    echo "Вставлено батч " . ($batchIndex + 1) . "/{$totalBatches}: " . count($insertData) . " моделей\n";
                }
            }
            
            echo "Всего вставлено моделей: {$insertedModels}\n";
        }

        $uniqueMarksCount = isset($uniqueMarks) ? count($uniqueMarks) : 0;
        echo "\n=== Итоги ===\n";
        echo "Обработано моделей из CSV: {$processed}\n";
        echo "Создано уникальных марок: {$uniqueMarksCount}\n";
        echo "Вставлено моделей в БД: {$insertedModels}\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%car_model}}');
        $this->truncateTable('{{%car_mark}}');
    }
}

