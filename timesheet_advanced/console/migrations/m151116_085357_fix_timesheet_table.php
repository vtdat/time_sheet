<?php

use yii\db\Schema;
use yii\db\Migration;

class m151116_085357_fix_timesheet_table extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%timesheet}}','status','int not null');
        $this->alterColumn('{{%timesheet}}','point','float');
    }

    public function down()
    {
        
        $this->alterColumn('{{%timesheet}}','status','int');
        $this->alterColumn('{{%timesheet}}','point','int');
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
