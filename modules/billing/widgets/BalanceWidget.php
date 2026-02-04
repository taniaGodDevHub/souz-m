<?php

namespace app\modules\billing\widgets;

use app\modules\billing\models\Account;
use yii\base\Widget;
use yii\bootstrap5\Html;

/**
 * Виджет баланса пользователя: сумма на счёте и кнопка перехода в кошелёк.
 *
 * Использование:
 * ```php
 * echo BalanceWidget::widget(['userId' => $userId]);
 * ```
 */
class BalanceWidget extends Widget
{
    /** @var int ID пользователя */
    public $userId;

    /** @var string URL кнопки «Кошелёк» (заглушка, если страница не создана) */
    public $walletUrl = '#';

    public function run()
    {
        $account = Account::findOne([
            'user_id' => $this->userId,
            'project_id' => null,
        ]);

        if (empty($account)) {
            $account = new Account();
            $account->user_id = $this->userId;
            $account->name = 'Счёт пользователя';
            $account->save();
        }

        $balance = $account->getBalanceInRubles();

        return $this->render('balance', [
            'balance' => $balance,
            'walletUrl' => $this->walletUrl,
        ]);
    }
}
