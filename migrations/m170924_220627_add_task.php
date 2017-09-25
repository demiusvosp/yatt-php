<?php

use yii\db\Migration;

class m170924_220627_add_task extends Migration
{
    public function safeUp()
    {
        $this->createTable('task', [
            'suffix' => $this->string(8)->comment('суффикс'),
            'id'     => $this->integer()->unsigned(), // primary key составной

            'caption' => $this->string(300)->comment('Заголовок'),
            'description' => $this->text()->comment('Описание'),

            'assigned_id' => $this->integer()->comment('Текущий пользователь, работающий над задачей'),

            'created_at' => $this->dateTime()->comment('Создана'),
            'updated_at' => $this->dateTime()->comment('Оновленна'),
        ]);

        $this->addPrimaryKey('pk', 'task', ['suffix', 'id']);
        $this->addForeignKey('fk-task-project-ref', 'task', 'suffix', 'project', 'suffix', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-task-user-ref', 'task', 'assigned_id', 'user', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-task-user-ref', 'task');
        $this->dropForeignKey('fk-task-project-ref', 'task');
        $this->dropTable('task');
    }

}
