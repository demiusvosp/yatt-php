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

        $projectAdmin = $auth->createRole('projectAdmin');// начинающиеся с project будут специальными, зависящеми от проекта.
        $projectAdmin->description = 'All access to project';
        $auth->add($projectAdmin);

        $projectEmployee = $auth->createRole('projectEmployee');
        $projectEmployee->description = 'Base role, to various project employee';
        $auth->add($projectEmployee);

        $projectViewer = $auth->createRole('projectViewer');
        $projectViewer->description = 'Only view project';
        $auth->add($projectViewer);

        $user = $auth->createRole('user');
        $user->description = 'other users, not assigned to project';
        $auth->add($user);

        // создаем пермишены
        //$userManagement = $auth->createPermission('userManagement'); это не действие, это раздел.
        //  В данный момент мы проверяем доступ к нему по роли. Возможно в будущем мы дадим доступ юзерам с полномочиями
        //    к частям раздела. (восстанавливать/заводить своих юзеров админам проекта например)


        // дадим системному пользователю все привилегии
        $auth->assign($root, 1);
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }

}
