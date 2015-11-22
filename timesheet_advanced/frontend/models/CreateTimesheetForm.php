<?php 

namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\Work;
use froetend\models\Timesheet;

/**
* Create Timesheet form
*/
class CreateTimesheetForm extends Model
{
	public $date;
	public $work_time;
	public $work_name;
	public $comment;
	public $process_id;
	public $team_id;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['process_id','team_id'], 'integer'],
                ['work_time', 'double'],
                [['work_name'], 'string', 'max' => 50],
                [['comment'], 'string', 'max' => 255],
                ['date','safe'],
        	[['date', 'work_time', 'work_name','process_id','team_id'], 'require'],
        	
        ];
    }
    public function attributeLabels()
    {
        return [
            'date' => 'Date',
            'team_id' => 'Team ID',
            'process_id' => 'Process ID',
            'work_time' => 'Work Time',
            'work_name' => 'Work Name',
            'comment' => 'Comment',
        ];
    }
    public function create(){
        $id=Yii::$app->user->identity->id;
        $timesheet=Timesheet::findTimesheet($id,$model->date);
       
    }
}
?>