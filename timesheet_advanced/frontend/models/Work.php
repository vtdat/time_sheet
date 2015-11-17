<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "work".
 *
 * @property integer $id
 * @property integer $timesheet_id
 * @property integer $team_id
 * @property integer $process_id
 * @property integer $work_time
 * @property string $work_name
 * @property string $comment
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Process $process
 * @property Team $team
 * @property Timesheet $timesheet
 */
class Work extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'work';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['work_time', 'work_name'], 'required'],
            [['timesheet_id', 'team_id', 'process_id', 'work_time', 'created_at', 'updated_at'], 'integer'],
            [['work_name'], 'string', 'max' => 50],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'timesheet_id' => 'Timesheet ID',
            'team_id' => 'Team ID',
            'process_id' => 'Process ID',
            'work_time' => 'Work Time',
            'work_name' => 'Work Name',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function attributes()
    {
        return array_merge(yii\db\ActiveRecord::attributes(), 
            [
                'timesheet.date',
                'process.process_name',
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcess()
    {
        return $this->hasOne(Process::className(), ['id' => 'process_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimesheet()
    {
        return $this->hasOne(Timesheet::className(), ['id' => 'timesheet_id']);
    }

    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id'])->via('timesheet');
    }
}
