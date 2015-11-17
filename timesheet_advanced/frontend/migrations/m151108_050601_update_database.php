<?php

use yii\db\Schema;
use yii\db\Migration;

class m151108_050601_update_database extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        //Bổ sung column cho bảng user
        $this->addColumn('{{%user}}','address','varchar(50)');
        $this->addColumn('{{%user}}','full_name','varchar(50)');
        $this->addColumn('{{%user}}','telephone','varchar(20)');
        $this->addColumn('{{%user}}','role','int not null');
        $this->addColumn('{{%user}}','birthday','date');
        $this->addColumn('{{%user}}','avatar','varchar(50)');
        
        //Tạo bảng timesheet
        $this->createTable('{{%timesheet}}',[
            'id'=>'pk',
            'user_id'=>'int not null',
            'point'=>'int',
            'director_comment'=>'string',
            'date'=>'date not null',
            'status'=>'int',
            'created_at'=>'int not null',
            'updated_at'=>'int not null',
        ], $tableOptions);
        
        //Tạo bảng work
        $this->createTable('{{%work}}',[
            'id'=>'pk',
            'timesheet_id'=>'int not null',
            'team_id'=>'int not null',
            'process_id'=>'int not null',
            'work_time'=>'int not null',
            'work_name'=>'varchar(50) not null',
            'comment'=>'string',
            'created_at'=>'int not null',
            'updated_at'=>'int not null',
        ], $tableOptions);
        
        //Tạo bảng team
        $this->createTable('{{%team}}',[
            'id'=>'pk',
            'team_name'=>'varchar(50) not null unique',
            'description'=>'string',
            'created_at'=>'int not null',
            'updated_at'=>'int not null',
        ], $tableOptions);
        //Tạo bảng process
        $this->createTable('{{%process}}',[
            'id'=>'pk',
            'process_name'=>'varchar(50) not null unique',
            'created_at'=>'int not null',
            'updated_at'=>'int not null',
        ], $tableOptions);
        //Tạo bảng team member
        $this->createTable('{{%team_member}}',[
            'id'=>'pk',
            'team_id'=>'int not null',
            'user_id'=>'int not null',
            'created_at'=>'int not null',
            'updated_at'=>'int not null',
        ], $tableOptions);
        
        //Tạo quan hệ
        $this->addForeignKey('fk_timesheet_userid','{{%timesheet}}','user_id','user','id');
        $this->addForeignKey('fk_work_timesheetid','{{%work}}','timesheet_id','timesheet','id');
        $this->addForeignKey('fk_work_teamid','{{%work}}','team_id','team','id');
        $this->addForeignKey('fk_work_processid','{{%work}}','process_id','process','id');
        $this->addForeignKey('fk_teammember_teamid','{{%team_member}}','team_id','team','id');
        $this->addForeignKey('fk_teammember_userid','{{%team_member}}','user_id','user','id');
        
        
    }   

    public function down()
    {
        $this->dropColumn('{{%user}}','address');
        $this->dropColumn('{{%user}}','full_name');
        $this->dropColumn('{{%user}}','telephone');
        $this->dropColumn('{{%user}}','role');
        $this->dropColumn('{{%user}}','birthday');
        $this->dropColumn('{{%user}}','avatar');
        
        $this->dropForeignKey('fk_timesheet_userid','{{%timesheet}}');
        $this->dropForeignKey('fk_work_timesheetid','{{%work}}');
        $this->dropForeignKey('fk_work_teamid','{{%work}}');
        $this->dropForeignKey('fk_work_processid','{{%work}}');
        $this->dropForeignKey('fk_teammember_teamid','{{%team_member}}');
        $this->dropForeignKey('fk_teammember_userid','{{%team_member}}');
        
        $this->dropTable('{{%timesheet}}');
        $this->dropTable('{{%process}}');
        $this->dropTable('{{%work}}');
        $this->dropTable('{{%team}}');
        $this->dropTable('{{%team_member}}');
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


