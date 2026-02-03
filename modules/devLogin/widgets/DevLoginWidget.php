<?php

namespace app\modules\devLogin\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap5\Dropdown;
use yii\bootstrap5\Html;

/**
 * Дропдаун «Войти как» со списком ролей для быстрого входа под test_[роль]. Только в dev.
 */
class DevLoginWidget extends Widget
{
    public function run()
    {
        if (!YII_ENV_DEV) {
            return '';
        }

        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $items = [];

        foreach ($roles as $roleName => $role) {
            $items[] = [
                'label' => $roleName,
                'url' => ['/devLogin/default/login', 'role' => $roleName],
            ];
        }

        if (empty($items)) {
            return '';
        }

        $button = Html::button(
            'Войти как (dev) ' . Html::tag('span', '', ['class' => 'caret']),
            [
                'class' => 'btn btn-outline-secondary btn-sm dropdown-toggle',
                'data-bs-toggle' => 'dropdown',
                'aria-expanded' => 'false',
            ]
        );
        $menu = Dropdown::widget([
            'items' => $items,
            'options' => ['class' => 'dropdown-menu dropdown-menu-end'],
        ]);
        return Html::tag('li', Html::tag('div', $button . $menu, ['class' => 'dropdown']), ['class' => 'nav-item']);
    }
}
