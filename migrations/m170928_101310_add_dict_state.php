<?php

use yii\db\Migration;

class m170928_101310_add_dict_state extends Migration
{
    public function safeUp()
    {
        $this->createTable('dict_state', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer(),
            'name' => $this->string(),
            'description' => $this->string(),
            'position'  => $this->integer()
        ]);

        $this->addForeignKey('fk-dict_state-project-ref', 'dict_state', 'project_id', 'project', 'id');
        $this->addColumn('task', 'state', $this->integer());
        $this->addForeignKey('fk-task-dict_state-ref', 'task', 'state', 'dict_state', 'id');

        // добавляем значения по умолчанию
// В консоли не работает Yii::t и ладно, эти задачи не наследуются от глобального, а скорее создаются сервисом справочника
//        $this->insert('dict_state', [
//            'project_id' => null,
//            'name'  => Yii::t('dicts', 'New'),
//            'description' => Yii::t('dicts', 'New task'),
//            'position' => 0
//        ]);
//        $this->insert('dict_state', [
//            'project_id' => null,
//            'name'  => Yii::t('dicts', 'Closed'),
//            'description' => Yii::t('dicts', 'Closed task'),
//            'position' => 1
//        ]);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-task-dict_state-ref', 'task');
        $this->dropColumn('task', 'state');
        $this->dropTable('dict_state');
    }
}
