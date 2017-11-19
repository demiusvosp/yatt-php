<?php

use app\migrations\DictMigration;

class m161109_000053_create_dict_difficulty extends DictMigration
{
    public $tableName = 'dict_difficulty';
    public $refField = 'dict_difficulty_id';

    public function safeUp()
    {
        parent::safeUp();
        $this->addColumn(
            $this->tableName,
            'ratio',
            $this->float()->unsigned()->defaultValue(1)
        );
    }

    public function safeDown()
    {
        parent::safeDown();
    }
}
