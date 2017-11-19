<?php

use app\migrations\DictMigration;

class m161109_000050_create_dict_stage extends DictMigration
{
    public $tableName = 'dict_stage';
    public $refField = 'dict_stage_id';

    public function safeUp()
    {
        parent::safeUp();
        $this->addColumn(
            $this->tableName,
            'type',
            $this->integer()->unsigned()->defaultValue(0)
        );
    }

    public function safeDown()
    {
        parent::safeDown();
    }
}
