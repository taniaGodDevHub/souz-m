<?php

use app\models\User;
use yii\db\Migration;

/**
 * Создаёт пользователей test_[РОЛЬ] для каждой роли и назначает им соответствующую роль.
 * Запуск: php yii migrate --migrationPath=@app/modules/devLogin/migrations
 */
class m260203_100000_create_test_users_by_roles extends Migration
{
    private const PASSWORD = 'test123';
    private const USERNAME_PREFIX = 'test_';

    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        foreach ($roles as $roleName => $role) {
            $username = self::USERNAME_PREFIX . $roleName;
            $user = User::findByUsername($username);

            if (!$user) {
                $user = new User();
                $user->username = $username;
                $user->email = $username . '@dev.local';
                $user->status = User::STATUS_ACTIVE;
                $user->setPassword(self::PASSWORD);
                $user->generateAuthKey();
                if (!$user->save(false)) {
                    echo "Не удалось создать пользователя: {$username}\n";
                    continue;
                }
            }

            $assignments = $auth->getAssignments($user->id);
            if (!isset($assignments[$roleName])) {
                $auth->assign($role, $user->id);
            }
        }

        return true;
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        foreach ($roles as $roleName => $role) {
            $username = self::USERNAME_PREFIX . $roleName;
            $user = User::findByUsername($username);
            if ($user) {
                $auth->revoke($role, $user->id);
                $user->delete();
            }
        }

        return true;
    }
}
