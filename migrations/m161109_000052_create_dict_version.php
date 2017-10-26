<?php

use app\migrations\DictMigration;

class m161109_000052_create_dict_version extends DictMigration
{
    public $tableName = 'dict_version';
    public $refFieldOpen  = 'dict_version_open_id';
    public $refFieldClose = 'dict_version_close_id';

    public function safeUp()
    {
        $this->upTable();
        $this->addColumn($this->tableName, 'type', 'integer');

        $this->addForeignKey(
            'fk-'.$this->tableName.'-project-ref',
            $this->tableName,
            'project_id',
            'project',
            'id'
        );

        $this->addColumn('task', $this->refFieldOpen, $this->integer());
        $this->addForeignKey(
            'fk-task-'.$this->tableName.'-open-ref',
            'task',
            $this->refFieldOpen,
            $this->tableName,
            'id'
        );

        $this->addColumn('task', $this->refFieldClose, $this->integer());
        $this->addForeignKey(
            'fk-task-'.$this->tableName.'-close-ref',
            'task',
            $this->refFieldClose,
            $this->tableName,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-task-'.$this->tableName.'-open-ref', 'task');
        $this->dropForeignKey('fk-task-'.$this->tableName.'-close-ref', 'task');
        $this->dropColumn('task', $this->refFieldOpen);
        $this->dropColumn('task', $this->refFieldClose);
        $this->downTable();
    }
}
