<?php

use yii\db\Migration;


/**
 * Class m171120_000060_create_comment
 */
class m171120_000060_create_comment extends Migration
{

    public $tableName = 'comment';


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id'           => $this->primaryKey(),
                'object_class' => $this->string(80)->notNull()->comment('Объект к которому крепится тред'),
                'object_id'    => $this->integer()->notNull()->comment('Id объекта'),
                'author_id'    => $this->integer(),
                'type'         => $this->integer()->unsigned()->notNull()->defaultValue(0),
                'text'         => $this->text(),
                'created_at'   => $this->dateTime()->comment('Создана'),
                'updated_at'   => $this->dateTime()->comment('Оновленна'),
            ]
        );

        $this->createIndex('idx-object', $this->tableName, ['object_class', 'object_id']);
        $this->createIndex('idx-type', $this->tableName, 'type');

        $this->addForeignKey(
            'fk-comment-user-ref',
            $this->tableName,
            'author_id',
            'user',
            'id'
        );
    }


    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-comment-user-ref', $this->tableName);
        $this->dropTable($this->tableName);
    }

}
