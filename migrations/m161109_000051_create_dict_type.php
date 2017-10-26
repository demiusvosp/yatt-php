<?php

use app\migrations\DictMigration;

class m161109_000051_create_dict_type extends DictMigration
{
    public $tableName = 'dict_type'; // не вижу смысла в {{%dict_type}}
    public $refField = 'dict_type_id';

    public function safeUp()
    {
        parent::safeUp();
    }

    public function safeDown()
    {
        parent::safeDown();
    }
}
