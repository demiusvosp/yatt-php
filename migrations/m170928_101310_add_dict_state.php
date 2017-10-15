<?php

use app\migrations\DictMigration;

class m170928_101310_add_dict_state extends DictMigration
{
    public $tableName = 'dict_state'; // не вижу смысла в {{%dict_type}}
    public $refField = 'dict_state_id';

    public function safeUp()
    {
        parent::safeUp(); // TODO: особенные поля и поведение справочника Этап задачи
    }

    public function safeDown()
    {
        parent::safeDown(); // TODO: особенные поля и поведение справочника Этап задачи
    }
}
