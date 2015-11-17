<?php

use yii\db\Schema;
use yii\db\Migration;

class m151114_064421_point_to_float extends Migration
{
    public function up()
    {
        $this->alterColumn('timesheet','point','float(2)');
    }

    public function down()
    {
        $this->alterColumn('timesheet','point','int');
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
