<?php

use yii\db\Migration;


/**
 * Class m171120_184330_create_comment
 */
class m171120_184330_create_comment extends Migration
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
                'id'         => $this->primaryKey(),
                'object'     => $this->string(80)->notNull()->comment('Объект к которому крепится тред'),
                'object_id'  => $this->integer()->notNull()->comment('Id объекта'),
                'author_id'  => $this->integer(),
                'type'       => $this->integer()->unsigned()->notNull()->defaultValue(0),
                'text'       => $this->text(),
                'created_at' => $this->dateTime()->comment('Создана'),
                'updated_at' => $this->dateTime()->comment('Оновленна'),
            ]
        );

        $this->createIndex('idx-object', $this->tableName, ['object', 'object_id']);
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
        $this->dropForeignKey('fk-comment-author-ref', $this->tableName);
        $this->dropTable($this->tableName);
    }

}
