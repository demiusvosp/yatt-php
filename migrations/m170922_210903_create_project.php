<?php

use yii\db\Migration;

class m170922_210903_create_project extends Migration
{
    public function safeUp()
    {
        $this->createTable('project', [
            'id'     => $this->primaryKey(),
            'suffix' => $this->string(8)->unique()->comment('суффикс'),
            'name'   => $this->string(255)->comment('Имя'),
            'description' => $this->text()->comment('Описание'),
            'public'      => $this->integer(255)->comment('0-всем, 1-только зарегистрированным, 2-только уполномоченным'),
            'created_at'  => $this->dateTime()->comment('Создана'),
            'updated_at'  => $this->dateTime()->comment('Оновленна'),
            'admin_id'    => $this->integer()->comment('основной админ проекта'),
            'config'      => $this->text()->comment('прочий конфиг'),

            'last_task_id' => $this->integer()->defaultValue(0)->comment('номер последней задачи'),
        ]);

        $this->addForeignKey('fk-project-user-ref', 'project', 'admin_id', 'user', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-project-user-ref', 'project');
        $this->dropTable('project');

        return true;
    }

}
