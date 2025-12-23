# Модуль биллинговой системы

Модульная биллинговая система для управления внутренними финансовыми операциями проекта.

## Установка

1. Модуль уже находится в `modules/billing/`

2. Модуль уже добавлен в `config/web.php`:
```php
'billing' => [
    'class' => 'app\modules\billing\Billing',
],
```

3. Выполните миграцию для создания таблиц:
```bash
php yii migrate --migrationPath=@app/modules/billing/migrations
```

## Структура модуля

```
modules/billing/
├── Billing.php                    # Класс модуля
├── controllers/
│   └── BillingController.php      # Контроллер
├── models/
│   ├── Account.php                 # Модель счета
│   ├── Transaction.php            # Модель транзакции
│   └── TransactionType.php       # Модель типа транзакции
├── helpers/
│   └── BillingHelper.php          # Helper класс
├── migrations/
│   └── m251222_170000_create_billing_tables.php
└── views/
    └── billing/
        ├── index.php              # Список счетов
        ├── view.php               # Просмотр счета
        ├── create-account.php    # Создание счета
        ├── update-account.php    # Редактирование счета
        ├── _form-account.php     # Форма счета
        ├── create-transaction.php # Создание транзакции
        ├── update-transaction.php # Редактирование транзакции
        └── _form-transaction.php  # Форма транзакции
```

## Использование

### Доступ к модулю

После установки модуль доступен по адресу: `/billing/billing/index`

### Основные функции

1. **Управление счетами:**
   - Создание счетов пользователей
   - Просмотр баланса
   - Редактирование и удаление счетов

2. **Управление транзакциями:**
   - Создание транзакций (пополнение, списание, перевод)
   - Просмотр истории транзакций
   - Редактирование и удаление транзакций

3. **Типы транзакций:**
   - Выплата аваркому
   - Выплата менеджеру
   - Возврат средств
   - Комиссия
   - Перевод между счетами
   - Пополнение счета
   - Списание со счета
   - Оплата услуг
   - Оплата от клиента
   - Штраф
   - Бонус

## API

### Получение баланса счета

```php
use app\modules\billing\models\Account;
use app\modules\billing\helpers\BillingHelper;

$account = Account::findOne($accountId);
$balance = $account->getBalanceInRubles();

// или через helper
$balance = BillingHelper::getAccountBalanceInRubles($accountId);
```

### Создание транзакции

```php
use app\modules\billing\models\Transaction;

$transaction = new Transaction();
$transaction->from_acc_id = $fromAccountId;
$transaction->to_acc_id = $toAccountId;
$transaction->transaction_type = $transactionTypeId;
$transaction->setAmountFromRubles(1000.50); // 1000.50 рублей
$transaction->save();
```

### Форматирование суммы

```php
use app\modules\billing\helpers\BillingHelper;

$formatted = BillingHelper::formatRubles(1234.56); // "1 234.56 ₽"
```

## Безопасность

- Пользователи могут работать только со своими счетами
- Администраторы имеют доступ ко всем счетам и системным счетам
- Все операции логируются в `runtime/logs/billing.log`

## Особенности

- Суммы хранятся в копейках (BIGINT) для точности расчетов
- Баланс вычисляется динамически (не хранится в БД)
- Поддержка системных счетов (user_id = 0)
- Поддержка счетов проектов (project_id заполнен)

## Интеграция

Модуль готов к интеграции с другими модулями:
- Автоматическое создание счетов при создании пользователей/проектов
- Автоматическое создание транзакций при выполнении задач
- Связь с документами (счета, акты, заказы)

## Логирование

Все операции биллинга логируются в `runtime/logs/billing.log` с категорией `billing`.

