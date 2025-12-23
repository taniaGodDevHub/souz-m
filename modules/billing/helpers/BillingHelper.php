<?php

namespace app\modules\billing\helpers;

use app\modules\billing\models\Account;
use Yii;

/**
 * Helper класс для работы с биллинговой системой
 */
class BillingHelper
{
    /**
     * Получить баланс счета в рублях
     * @param int|Account $accountId ID счета или объект Account
     * @return float
     */
    public static function getAccountBalanceInRubles($accountId)
    {
        if ($accountId instanceof Account) {
            $account = $accountId;
        } else {
            $account = Account::findOne($accountId);
        }

        if (!$account) {
            return 0.0;
        }

        return $account->getBalanceInRubles();
    }

    /**
     * Форматировать сумму в рублях для отображения
     * @param float $amount
     * @return string
     */
    public static function formatRubles($amount)
    {
        return number_format($amount, 2, '.', ' ') . ' ₽';
    }

    /**
     * Преобразовать рубли в копейки
     * @param float $rubles
     * @return int
     */
    public static function rublesToKopecks($rubles)
    {
        return (int)round($rubles * 100);
    }

    /**
     * Преобразовать копейки в рубли
     * @param int $kopecks
     * @return float
     */
    public static function kopecksToRubles($kopecks)
    {
        return $kopecks / 100;
    }
}

