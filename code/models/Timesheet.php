<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "timesheet".
 *
 * @property integer $id
 * @property integer $user_id
 * @property double $point
 * @property string $comment
 * @property string $date
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
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
            [['id', 'user_id', 'date', 'status', 'created_at', 'updated_at'], 'required'],
            [['id', 'user_id', 'status'], 'integer'],
            [['point'], 'number'],
            [['comment'], 'string'],
            [['date', 'created_at', 'updated_at'], 'safe']
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
            'comment' => 'Comment',
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
