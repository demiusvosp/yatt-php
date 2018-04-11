<?php

use yii\db\Migration;

class m161109_000030_create_project extends Migration
{
    public function safeUp()
    {
        $this->createTable('project', [
            'id'     => $this->primaryKey(),
            'suffix' => $this->string(8)->unique()->comment('суффикс'),
            'name'   => $this->string(255)->comment('Имя'),
            'archived' => $this->boolean()->defaultValue(false)->comment('В архиве'),
            'description' => $this->text()->comment('Описание'),
            'created_at'  => $this->dateTime()->comment('Создана'),
            'updated_at'  => $this->dateTime()->comment('Оновленна'),
            'admin_id'    => $this->integer()->comment('основной админ проекта'),
            'config'      => $this->text()->comment('прочий конфиг'),

            'last_task_index' => $this->integer()->defaultValue(1)->comment('номер последней задачи'),
        ]);

        $this->createIndex('name', 'project', 'name', true);
        $this->createIndex('archived', 'project', 'archived', false);
        $this->addForeignKey('fk-project-user-ref', 'project', 'admin_id', 'user', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-project-user-ref', 'project');
        $this->dropTable('project');

        return true;
    }

}
