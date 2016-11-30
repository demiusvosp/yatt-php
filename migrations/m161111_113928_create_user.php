<?php

use yii\db\Migration;
use app\models\User;

class m161111_113928_create_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string(32),
            'user_token' => $this->string(),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('idx-user-username', '{{%user}}', 'username');
        $this->createIndex('idx-user-email', '{{%user}}', 'email');
        $this->createIndex('idx-user-status', '{{%user}}', 'status');

        // создадим сразу суперадмина. Не факт, что это стоит делать здесь, но разберемся, когда займемся установщиком
        $root = new User();
        $root->username = 'admin';
        $root->email = Yii::$app->params['adminEmail'];
        $root->status = User::STATUS_ACTIVE;
        $root->setPassword('admin');

        $root->save();
    }

    public function down()
    {
        $this->dropTable('{{%user}}');

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}