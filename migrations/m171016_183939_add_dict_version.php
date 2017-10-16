<?php

use app\migrations\DictMigration;

class m171016_183939_add_dict_version extends DictMigration
{
    public $tableName = 'dict_version';
    public $refField = 'dict_version_id';

    public function safeUp()
    {
        parent::safeUp(); // TODO: особенные поля и поведение справочника Тип задачи
    }

    public function safeDown()
    {
        parent::safeDown(); // TODO: особенные поля и поведение справочника Тип задачи
    }
}
