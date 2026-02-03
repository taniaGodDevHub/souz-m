<?php

namespace app\helpers;

use Yii;

/**
 * Возвращает URL личного кабинета по роли пользователя.
 */
class CabinetHelper
{
    /** Роли с личными кабинетами: модуль => приоритет (меньше = выше) */
    private const ROLE_MODULES = [
        'admin' => 0,
        'manager' => 1,
        'advertiser' => 2,
        'partner' => 3,
    ];

    /**
     * URL по имени роли.
     */
    public static function getUrlByRole(string $role): array
    {
        $module = isset(self::ROLE_MODULES[$role]) ? $role : 'partner';
        return ["/{$module}/default/index"];
    }

    /**
     * URL личного кабинета для пользователя по его назначенным ролям.
     */
    public static function getDefaultUrlForUser(int $userId): array
    {
        $assignments = Yii::$app->authManager->getAssignments($userId);
        $bestRole = null;
        $bestPriority = 999;
        foreach (array_keys($assignments) as $role) {
            if (isset(self::ROLE_MODULES[$role]) && self::ROLE_MODULES[$role] < $bestPriority) {
                $bestPriority = self::ROLE_MODULES[$role];
                $bestRole = $role;
            }
        }
        $module = $bestRole ?? 'partner';
        return ["/{$module}/default/index"];
    }
}
