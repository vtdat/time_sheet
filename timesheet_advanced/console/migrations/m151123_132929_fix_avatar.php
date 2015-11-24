<?php

use yii\db\Schema;
use yii\db\Migration;

class m151123_132929_fix_avatar extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%user}}','avatar');
        $this->addColumn('{{%user}}','avatar','varchar(1000)');
    }

    public function down()
    {
        $this->dropColumn('{{%user}}','avatar');
        $this->addColumn('{{%user}}','avatar','varchar(50)');
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
