<?php

use app\migrations\DictMigration;

class m170928_101310_add_dict_stage extends DictMigration
{
    public $tableName = 'dict_stage'; // не вижу смысла в {{%dict_type}}
    public $refField = 'dict_stage_id';

    public function safeUp()
    {
        parent::safeUp(); // TODO: особенные поля и поведение справочника Этап задачи
    }

    public function safeDown()
    {
        parent::safeDown(); // TODO: особенные поля и поведение справочника Этап задачи
    }
}
