<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use app\models\Timesheet;
use app\models\Work;
/**
 * Signup form
 */
class WorkForm extends Model
{
    public $user_id;
    public $date;
    public $team_id;
    public $process_id;
    public $work_name;
    public $work_time;
    public $comment;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','date','team_id', 'process_id', 'work_time', 'work_name',], 'required'],
            [['team_id', 'process_id'], 'integer'],
            [['work_time'], 'double'],
            [['work_name'], 'string', 'max' => 50],
            [['comment'], 'string', 'max' => 255]
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function checkTimesheet(){
        $model = Timesheet::findTimesheet($this->user_id,$this->date);
        if($model !== null){
            if ($model->point==null){
                return $model;
            }
            else{
                return false;
            }
        }
        else{
            return null;
        }
        
    }
    
    public function save()
    {
       if($this->validate()){
           $model = checkTimesheet();
           //nếu timesheet khác false
            if($model != false){
                if($model==null){
                    $model = new Timesheet([
                        'user_id'=>$this->user_id,
                        'date'=>$this->date,
                        'status'=>0,
                     ]);
                    $model->save();
                }
                $work = new Work([
                        'timesheet_id'=>$model->id,
                        'team_id'=>$this->team_id,
                        'process_id'=>$this->process_id,
                        'work_time'=>$this->work_time,
                        'work_name'=>$this->work_name,
                    ]);
                if ($work->save()){
                        return $model;
                }
            }
            else{
                return null;
            }
            return null;
       }
       return null;
    }
}
