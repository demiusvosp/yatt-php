<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 15.10.17
 * Time: 14:32
 */
namespace app\migrations;

use \yii\db\Migration;

/**
 * Class DictMigration справочники штука практически одинаковая, вынесем общий функционал
 */
class DictMigration extends Migration
{
    public function safeUp()
    {
        $this->upTable();
        $this->upForeignKeys();
    }


    public function safeDown()
    {
        $this->downForeignKeys();
        $this->downTable();
    }


    public function upTable()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer(),
            'name' => $this->string(),
            'description' => $this->string(),
            'position'  => $this->integer()->defaultValue(0)
        ]);
    }


    public function downTable()
    {
        $this->dropTable($this->tableName);
    }


    public function upForeignKeys()
    {
        $this->addForeignKey(
            'fk-'.$this->tableName.'-project-ref',
            $this->tableName,
            'project_id',
            'project',
            'id'
        );

        $this->addColumn('task', $this->refField, $this->integer());
        $this->addForeignKey(
            'fk-task-'.$this->tableName.'-ref',
            'task',
            $this->refField,
            $this->tableName,
            'id'
        );
    }


    public function downForeignKeys()
    {
        $this->dropForeignKey('fk-task-'.$this->tableName.'-ref', 'task');
        $this->dropColumn('task', $this->refField);
    }

}
