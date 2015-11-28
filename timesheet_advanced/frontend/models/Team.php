<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "team".
 *
 * @property integer $id
 * @property string $team_name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property TeamMember[] $teamMembers
 * @property Work[] $works
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team';
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
            [['team_name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['team_name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['team_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_name' => 'Team Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamMembers()
    {
        return $this->hasMany(TeamMember::className(), ['team_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorks()
    {
        return $this->hasMany(Work::className(), ['team_id' => 'id']);
    }
    
    public static function getTeamName($teamid){
        return Team::findOne($teamid)->team_name;
    }
    
}
