<?php

use yii\db\Migration;
use app\helpers\Access;
use app\components\AuthProjectManager;

class m161109_000010_create_base_permissions extends Migration
{
    public function safeUp()
    {
        /** @var AuthProjectManager $auth */
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
        /** @var AuthProjectManager $auth */
        $auth = Yii::$app->get('authManager');

        $auth->removeAll();
    }

}
