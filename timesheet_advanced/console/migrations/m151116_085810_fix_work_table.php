<?php

use yii\db\Schema;
use yii\db\Migration;

class m151116_085810_fix_work_table extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%work}}','work_time','float not null');
    }

    public function down()
    {
        $this->alterColumn('{{%work}}','work_time','int not null');
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
