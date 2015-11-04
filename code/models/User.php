<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $user_name
 * @property string $password
 * @property string $full_name
 * @property integer $phone
 * @property string $email
 * @property integer $role
 * @property string $avatar
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TeamMember[] $teamMembers
 * @property Timesheet[] $timesheets
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_name', 'password'], 'required', 'message' => 'Không được bỏ trống !'],
            [['id', 'phone', 'role'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_name'], 'string', 'max' => 30],
            [['password', 'full_name', 'email'], 'string', 'max' => 50],
            [['avatar'], 'string', 'max' => 100],
            [['user_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_name' => 'Tài khoản',
            'password' => 'Mật khẩu',
            'full_name' => 'Full Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'role' => 'Role',
            'avatar' => 'Avatar',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamMembers()
    {
        return $this->hasMany(TeamMember::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimesheets()
    {
        return $this->hasMany(Timesheet::className(), ['user_id' => 'id']);
    }

    public function Login ($username, $password){
        $login = User::find()->where(['user_name'=>$username, 'password'=>$password]) -> count();
        if ($login == 1) {
            return true;
        } else return false;
    }

    public function submit()
    {
        if ($this->validate()) {
            $user = new User();
            $user->id = $this->id;
            $user->user_name = $this->user_name;
            $user->password = $this->password;
            $user->save();

            return true;
        }
        return false;
    }
}

