<?php

use app\migrations\DictMigration;

class m170928_101310_create_dict_stage extends DictMigration
{
    public $tableName = 'dict_stage';
    public $refField = 'dict_stage_id';

    public function safeUp()
    {
        parent::safeUp();
        $this->addColumn($this->tableName, 'type', $this->integer()->unsigned());
    }

    public function safeDown()
    {
        parent::safeDown();
    }
}
