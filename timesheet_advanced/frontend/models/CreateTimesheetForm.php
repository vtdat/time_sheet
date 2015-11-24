<?php 

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
* Create Timesheet form
*/
class CreateTimesheetForm extends Model
{
	public $date;
	public $work_time;
	public $work_name;
	public $comment;
	public $process;
	public $team;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	[['date', 'work_time', 'work_name'], 'require'],
        	['work_time', 'float'],
        ];
    }
}

?>