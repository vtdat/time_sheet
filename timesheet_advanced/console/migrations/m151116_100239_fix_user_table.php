<?php

use yii\db\Schema;
use yii\db\Migration;

class m151116_100239_fix_user_table extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%user}}','role','int not null default 0');
    }

    public function down()
    {
        $this->alterColumn('{{%user}}','role','int not null');
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
