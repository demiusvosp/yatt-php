<?php

use yii\db\Migration;
use app\components\auth\Accesses;
use app\components\auth\AuthProjectManager;

class m161109_000010_create_base_permissions extends Migration
{
    public function safeUp()
    {
        /** @var AuthProjectManager $auth */
        $auth = Yii::$app->get('authManager');

        // создаем роли
        $root = $auth->addRole(Accesses::ROOT);
        $auth->addRole(Accesses::USER, [$root]);

        // создаем пермишены
        $auth->addPermission(
            Accesses::USER_MANAGEMENT,
            [$root]
        );
        $auth->addPermission(
            Accesses::PROJECT_MANAGEMENT,
            [$root]
        );
        $auth->addPermission(
            Accesses::ACCESS_MANAGEMENT,
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
