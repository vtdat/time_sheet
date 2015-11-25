<?php


namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use frontend\models\Team;

/**
 * This is the model class for table "team_member".
 *
 * @property integer $id
 * @property integer $team_id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Team $team
 * @property User $user
 */
class TeamMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team_member';
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

            [['team_id', 'user_id'], 'required'],

            [['team_id', 'user_id', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_id' => 'Team ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    
    public function getTeamName($teamid){
        return Team::findOne(['id'=>$teamid])->team_name;
    }
    
    public function getObjectById($teamid,$userid){
        return TeamMember::findOne(['team_id'=>$teamid,'user_id'=>$userid]);
    }
   
    public function getTeamListByUser($userid){
        $teamlist=TeamMember::find()->where(['user_id'=>$userid])->all();
        $teamid=[];
        foreach($teamlist as $team){
            $teamid[]=$team->team_id;
        }     
        return $teamid;
    }
    public function deleteAllTeam($userid){
        $teams=TeamMember::find()->where(['user_id'=>$userid])->all();
        foreach($teams as $team){
            $team->delete();
        }
    }
}
