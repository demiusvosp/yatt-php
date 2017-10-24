<?php

use app\migrations\DictMigration;

class m171018_184426_create_dict_category extends DictMigration
{
    public $tableName = 'dict_category';
    public $refField  = 'dict_category_id';

    /*
     * Потом тут будут перепоределены стандартные таблицы для создания дерева, может nested set, может другого.
     */
}
