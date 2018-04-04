<?php

use yii\db\Migration;
use app\components\auth\Role;
use app\components\auth\Permission;
use app\components\auth\AuthProjectManager;

class m161109_000010_create_base_permissions extends Migration
{
    public function safeUp()
    {
        /** @var AuthProjectManager $auth */
        $auth = Yii::$app->get('authManager');

        // создаем роли
        $root = $auth->addRole(Role::ROOT);
        $user = $auth->addRole(Role::USER, null, [$root]);
        $auth->addRole(Role::GUEST, null, [$user]);

        // создаем пермишены
        $auth->addPermission(
            Permission::MANAGEMENT_USER,
            null,
            [$root]
        );
        $auth->addPermission(
            Permission::MANAGEMENT_PROJECT,
            null,
            [$root]
        );
        $auth->addPermission(
            Permission::MANAGEMENT_ACCESS,
            null,
            [$root]
        );
    }

    public function safeDown()
    {
        /** @var AuthProjectManager $auth */
        $auth = Yii::$app->get('authManager');

        $auth->removeAll();
    }

}
