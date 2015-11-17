<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "timesheet".
 *
 * @property integer $id
 * @property integer $user_id
 * @property double $point
 * @property string $director_comment
 * @property string $date
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property Work[] $works
 */
class Timesheet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'timesheet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'required'],
            [['user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['date'], 'safe'],
            [['point'], 'number'],
            [['director_comment'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'point' => 'Point',
            'director_comment' => 'Director Comment',
            'date' => 'Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorks()
    {
        return $this->hasMany(Work::className(), ['timesheet_id' => 'id']);
    }
}
