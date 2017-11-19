<?php

use yii\db\Migration;

class m161109_000040_create_task extends Migration
{
    public function safeUp()
    {
        $this->createTable('task', [
            'id'     => $this->primaryKey(), // уникальный id (общий на все проекты (на случай переноса задачи

            'suffix' => $this->string(8)->comment('суффикс'),
            'index'  => $this->integer()->unsigned(), // естестественный ключ, составной

            'caption' => $this->string(300)->comment('Заголовок'),
            'description' => $this->text()->comment('Описание'),

            'assigned_id' => $this->integer()->comment('Текущий пользователь, работающий над задачей'),

            'priority'  =>  $this->integer()->comment('Приоритет задачи'),
            'progress'  =>  $this->integer()->comment('Прогресс выполнения'),

            'is_closed' => $this->boolean()->notNull()->comment('Задача закрыта')->defaultValue(false),
            'close_reason' => $this->integer()->comment('Причина закрытия'),

            'created_at' => $this->dateTime()->comment('Создана'),
            'updated_at' => $this->dateTime()->comment('Оновленна'),
        ]);

        $this->createIndex('index', 'task', ['suffix', 'index'], true);
        $this->createIndex('priority', 'task', ['priority']);
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
