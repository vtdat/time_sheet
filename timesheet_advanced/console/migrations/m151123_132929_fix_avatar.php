<?php

use yii\db\Schema;
use yii\db\Migration;

class m151123_132929_fix_avatar extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%user}}','avatar','varchar(1000)');
    }

    public function down()
    {
        $this->alterColumn('{{%user}}','avatar','varchar(50)');
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
