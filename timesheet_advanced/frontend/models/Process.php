<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "process".
 *
 * @property integer $id
 * @property string $process_name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Work[] $works
 */
class Process extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'process';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['process_name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['process_name'], 'string', 'max' => 50],
            [['process_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'process_name' => 'Process Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorks()
    {
        return $this->hasMany(Work::className(), ['process_id' => 'id']);
    }
}
