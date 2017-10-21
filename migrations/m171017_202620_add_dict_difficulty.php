<?php

use app\migrations\DictMigration;

class m171017_202620_add_dict_difficulty extends DictMigration
{
    public $tableName = 'dict_difficulty';
    public $refField = 'dict_difficulty_id';

    public function safeUp()
    {
        parent::safeUp();
        $this->addColumn($this->tableName, 'ratio', $this->float());
    }

    public function safeDown()
    {
        parent::safeDown();
    }
}
