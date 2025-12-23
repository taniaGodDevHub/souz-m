# Модуль управления страховыми компаниями

Модуль для хранения и управления информацией о страховых компаниях - действительных членах РСА.

## Установка

1. Модуль уже находится в `modules/insurance_companies/`

2. Модуль уже добавлен в `config/web.php`:
```php
'insurance_companies' => [
    'class' => 'app\modules\insurance_companies\InsuranceCompanies',
],
```

3. Выполните миграцию для создания таблицы:
```bash
php yii migrate --migrationPath=@app/modules/insurance_companies/migrations
```

## Структура модуля

```
modules/insurance_companies/
├── InsuranceCompanies.php                    # Класс модуля
├── controllers/
│   └── InsuranceCompanyController.php        # Контроллер
├── models/
│   ├── InsuranceCompany.php                   # Модель страховой компании
│   └── InsuranceCompanySearch.php            # Модель поиска
├── migrations/
│   └── m251222_180000_create_insurance_company_table.php
└── views/
    └── insurance-company/
        ├── index.php                          # Список компаний
        ├── view.php                           # Просмотр компании
        ├── create.php                         # Создание компании
        ├── update.php                         # Редактирование компании
        └── _form.php                          # Форма компании
```

## Поля таблицы

- **id** - ID записи
- **full_name** - Полное наименование (обязательное)
- **short_name** - Краткое наименование
- **previous_name** - Прежнее наименование
- **license_number** - Номер Лицензии Минфина
- **license_date** - Дата Лицензии Минфина
- **rsa_certificate_number** - Номер Свидетельства РСА
- **rsa_certificate_date** - Дата Свидетельства РСА
- **phone_fax** - Основной телефон/факс
- **email** - E-mail общий
- **created_at** - Дата создания
- **updated_at** - Дата обновления

## Использование

### Доступ к модулю

После установки модуль доступен по адресу: `/insurance_companies/insurance-company/index`

Или через меню: **Списки → Страховые компании** (для администраторов)

### Основные функции

1. **Просмотр списка компаний:**
   - Таблица со всеми страховыми компаниями
   - Поиск и фильтрация по всем полям
   - Сортировка по полям

2. **Создание компании:**
   - Форма с валидацией
   - Обязательное поле: Полное наименование
   - Валидация email

3. **Редактирование компании:**
   - Изменение всех полей
   - Сохранение истории изменений

4. **Просмотр детальной информации:**
   - Все поля компании
   - Форматированные даты

5. **Удаление компании:**
   - С подтверждением

## API

### Получение списка компаний

```php
use app\modules\insurance_companies\models\InsuranceCompany;

$companies = InsuranceCompany::find()
    ->orderBy(['full_name' => SORT_ASC])
    ->all();
```

### Поиск компании

```php
$company = InsuranceCompany::find()
    ->where(['license_number' => '12345'])
    ->one();
```

### Создание компании

```php
$company = new InsuranceCompany();
$company->full_name = 'ООО "Страховая компания"';
$company->short_name = 'СК';
$company->license_number = '12345';
$company->license_date = '2024-01-01';
$company->email = 'info@company.ru';
$company->save();
```

## Особенности

- Автоматическое логирование дат создания и обновления
- Валидация email адресов
- Форматирование дат для отображения
- Поиск по всем полям
- Индексы для быстрого поиска по ключевым полям

