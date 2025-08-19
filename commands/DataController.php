<?php

namespace app\commands;

use app\models\City;
use app\models\Dealers;
use app\models\PhotoCdn;
use app\models\SouzProduct;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\httpclient\Client;

class DataController extends Controller
{

    public $enableCsrfValidation = false;
    public function actionGetCities()
    {
        $client = new Client();
        $result =  $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://souz-m.ru/getCities.php')
            ->send();

        $result = json_decode($result->getContent());

        foreach ($result as $city) {

            $model = City::find()->where(['id' => $city->Message_ID])->one();
            if(empty($model)) {
                $model = new City();
            }

            $model->id = $city->Message_ID;
            $model->name = $city->CityName;
            $model->save();
        }
        return ExitCode::OK;
    }

    public function actionGetDealers()
    {
        $client = new Client();
        $result =  $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://souz-m.ru/getDealers.php')
            ->send();
        //var_dump($result->getContent());
        $result = json_decode($result->getContent());

        foreach ($result as $dealer) {

            $model = Dealers::find()->where(['id' => $dealer->Message_ID])->one();
            if(empty($model)) {
                $model = new Dealers();
            }

            $model->city_id = $dealer->City_ID;
            $model->name = $dealer->Name;
            $model->address  = $dealer->Address;
            $model->phone  = $dealer->Phone;
            $model->email  = $dealer->Email;
            $model->save();
        }
        return ExitCode::OK;
    }

    public function actionGetProducts()
    {
        $client = new Client();
        $result =  $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://souz-shop.ru/get_products_json.php')
            ->send();

        $result = json_decode($result->getContent());

        foreach ($result as $r) {
            $p = SouzProduct::find()
                ->where(['ex_id' => $r->id])
                ->one();

            if(empty($p)) {
                $p = new SouzProduct();
                $p->ex_id = $r->id;
            }

            $p->description = $r->description;
            $p->sku = $r->sku;
            $p->excerpt = $r->excerpt;
            $p->price = $r->price;
            $p->regular_price = $r->regular_price;
            $p->sale_price = $r->sale_price;
            $p->stock_status = $r->stock_status;
            $p->categories = serialize($r->categories);
            $p->subcategory = serialize($r->subcategory);
            $p->thumbnail_url = $r->thumbnail_url;
            $p->gallery = serialize($r->gallery);
            $p->permalink = $r->permalink;
            $p->attributes = serialize($r->attributes);
            $p->date_update = time();
            $p->save();

            $this->checkPhoto($p->thumbnail_url);
            foreach ($r->gallery as $g) {
                $this->checkPhoto($g);
            }
        }
        return ExitCode::OK;
    }


    /**
     * Функция проверки наличия фотографии и скачивания, если отсутствует
     *
     * @param string $link
     * @return bool Возвращает true в случае успеха
     */
    public function checkPhoto(string $link): bool
    {
        //$link = 'https://souz-shop.ru/wp-content/uploads/2025/04/7b5a0431_web.jpg-1.webp';
        echo "link: $link \n";
        try {
            // Пытаемся выбрать запись из таблицы photo_cdn по внешней ссылке
            $record = PhotoCdn::find()
                ->where(['ex_link' => $link])
                ->one();

            if ($record !== null) {
                echo "Запись есть \n";
                // Запись найдена, проверяем наличие внутреннего пути
                if (!empty($record['in_link'])) {
                    echo "Ссылка заполнена \n";
                    // Внутренний путь указан, проверяем существование файла
                    if (file_exists(Yii::getAlias('@uploads'). '/'.$record['in_link'])) {
                        echo "Файл существует \n";
                        return true; // Всё хорошо, файл найден
                    }
                }
            }

            // Если файл не найден или внутренняя ссылка пуста, начинаем процесс скачивания
            // Определяем имя файла по последней части URL
            $filename = basename(parse_url($link, PHP_URL_PATH));
            echo "Имя файла $filename \n";
            $firstThreeChars = substr($filename, 0, 3);
            echo "3 первые буквы $firstThreeChars \n";
            echo "uploads ".Yii::getAlias('@uploads')." \n";

            // Формируем директорию для сохранения файла
            $uploadDir = Yii::getAlias('@uploads') . '/' . $firstThreeChars;
            echo "Грузить будем в $uploadDir \n";

            // Создаём директорию, если её нет
            if (!is_dir($uploadDir)) {
                echo "Папки ещё нет \n";
                mkdir($uploadDir, 0777, true); // рекурсивно создаём директорию с нужными правами
            }

            // Генерируем внутренний путь к файлу
            $internalPath = "$uploadDir/$filename";
            echo "Внутренний путь к файлу $internalPath \n";

            // Скачиваем файл
            $ch = curl_init($link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $fileData = curl_exec($ch);
            curl_close($ch);

            // Сохраняем файл на диск
            file_put_contents($internalPath, $fileData);

            // Форматируем путь относительно веб-доступа
            $relativeInternalPath = str_replace(Yii::getAlias('@uploads'), '', $internalPath);

            // Создаём новую запись в таблице или обновляем существующую
            if ($record === null) {
                // Новая запись
                $record = new PhotoCdn();
                $record->ex_link = $link;
                $record->in_link = $relativeInternalPath;
                $record->save();
            } else {
                // Обновление существующей записи
                $record->in_link = $relativeInternalPath;
                $record->save();
            }

            return true;
        } catch (\Throwable $th) {
            Yii::error($th->getMessage());
            return false;
        }
    }
}
