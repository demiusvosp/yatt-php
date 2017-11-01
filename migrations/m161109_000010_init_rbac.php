<?php

use yii\db\Migration;
use app\helpers\Access;
use app\components\AccessManager;

class m161109_000010_init_rbac extends Migration
{
    public function safeUp()
    {
        /** @var AccessManager $auth */
        $auth = Yii::$app->get('authManager');

        // создаем роли
        $root = $auth->addRole(Access::ROOT);
        $user = $auth->addRole(Access::USER, [$root]);

        // создаем пермишены
        $auth->addPermission(
            Access::USER_MANAGEMENT,
            [$root]
        );
        $auth->addPermission(
            Access::PROJECT_MANAGEMENT,
            [$root]
        );
        $auth->addPermission(
            Access::ACCESS_MANAGEMENT,
            [$root]
        );
    }

    public function safeDown()
    {
        /** @var AccessManager $auth */
        $auth = Yii::$app->get('authManager');

        $auth->removeAll();
    }

}
