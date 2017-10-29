<?php

use yii\db\Migration;
use app\helpers\Access;
use app\components\AccessService;

class m161109_000010_init_rbac extends Migration
{
    public function safeUp()
    {
        /** @var AccessService $auth */
        $auth = Yii::$app->get('accessService');

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
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }

}
