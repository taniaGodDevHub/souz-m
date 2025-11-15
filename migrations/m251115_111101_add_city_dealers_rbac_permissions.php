<?php

use yii\db\Migration;

/**
 * Handles adding RBAC permissions for City and Dealers controllers to admin role.
 */
class m251115_111101_add_city_dealers_rbac_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Получаем роль admin
        $adminRole = $auth->getRole('admin');
        if (!$adminRole) {
            throw new \Exception('Роль admin не найдена. Создайте её перед выполнением миграции.');
        }

        // Разрешения для City
        $permissions = [
            'city_index',
            'city_view',
            'city_create',
            'city_update',
            'city_delete',
            'dealers_index',
            'dealers_view',
            'dealers_create',
            'dealers_update',
            'dealers_delete',
        ];

        foreach ($permissions as $permissionName) {
            // Проверяем, существует ли разрешение
            $permission = $auth->getPermission($permissionName);
            
            if (!$permission) {
                // Создаем разрешение
                $permission = $auth->createPermission($permissionName);
                $permission->description = $permissionName;
                $auth->add($permission);
            }

            // Назначаем разрешение роли admin, если еще не назначено
            if (!$auth->hasChild($adminRole, $permission)) {
                $auth->addChild($adminRole, $permission);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $permissions = [
            'city_index',
            'city_view',
            'city_create',
            'city_update',
            'city_delete',
            'dealers_index',
            'dealers_view',
            'dealers_create',
            'dealers_update',
            'dealers_delete',
        ];

        $adminRole = $auth->getRole('admin');
        
        if ($adminRole) {
            foreach ($permissions as $permissionName) {
                $permission = $auth->getPermission($permissionName);
                if ($permission && $auth->hasChild($adminRole, $permission)) {
                    $auth->removeChild($adminRole, $permission);
                }
                if ($permission) {
                    $auth->remove($permission);
                }
            }
        }
    }
}

