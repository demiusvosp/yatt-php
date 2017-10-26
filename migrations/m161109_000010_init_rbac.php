<?php

use yii\db\Migration;

class m161109_000010_init_rbac extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // создаем роли
        $root = $auth->createRole('root');
        $root->description = 'Root user with access all';
        $auth->add($root);

        $user = $auth->createRole('user');
        $user->description = 'other users, not assigned to project';
        $auth->add($user);
        $auth->addChild($root, $user);

        // создаем пермишены
        $userManagement = $auth->createPermission('userManagement');
        $userManagement->description = 'access to admin/userManager';
        $auth->add($userManagement);
        $auth->addChild($root, $userManagement);

        $projectManagement = $auth->createPermission('projectManagement');
        $projectManagement->description = 'access to admin/projectManager';
        $auth->add($projectManagement);
        $auth->addChild($root, $projectManagement);

        $accessManagement = $auth->createPermission('accessManagement');
        $accessManagement->description = 'access to admin/accessManagement';
        $auth->add($accessManagement);
        $auth->addChild($root, $accessManagement);

    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }

}
