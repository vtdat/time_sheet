<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use frontend\models\TeamMember;
use frontend\models\Timesheet;
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $address
 * @property string $full_name
 * @property string $telephone
 * @property integer $role
 * @property string $birthday
 * @property string $avatar
 *
 * @property TeamMember[] $teamMembers
 * @property Timesheet[] $timesheets
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * Array defining user's role
     * 0 --> User
     * 1 --> Admin
     * 2 --> Director (highest priority)
     */
    public static $roles = [
        ['key' => 0, 'role' => 'User'],
        ['key' => 1, 'role' => 'Admin'],
        ['key' => 2, 'role' => 'Director'],
    ];

    /**
     * Array defining user'status
     * 10 --> Working (default)
     *  9 --> Temporarily absent
     *  8 --> Long absent
     *  7 --> Retired
     */
    public static $status = [
        ['key' => 10, 'status' => 'Working'],
        ['key' =>  9, 'status' => 'Temporarily absent'],
        ['key' =>  8, 'status' => 'Long absent'],
        ['key' =>  7, 'status' => 'Retired'],
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public $password;
    public $team;
    public $imageFile;
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['status', 'role'], 'integer'],
            [['created_at', 'updated_at', 'birthday'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['address', 'full_name', 'avatar'], 'string', 'max' => 50],
            [['full_name'], 'required'],
            [['telephone'], 'string', 'max' => 20],
            ['username', 'unique','message' => 'This username  has already been taken.'],
            ['email', 'unique','message' => 'This email address has already been taken.'],
            [['email'], 'email'],
            [['password_reset_token'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['password'],'string','min'=>6],
            [['password'], 'required'],
            [['team'],'safe'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif', 'checkExtensionByMimeType'=>false,'maxSize' => 1024 * 1024 * 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'address' => 'Address',
            'full_name' => 'Full Name',
            'telephone' => 'Telephone',
            'role' => 'Role',
            'birthday' => 'Birthday',
            'avatar' => 'Avatar',
            'team' => 'Đăng ký team của bạn: ',
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

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public static function getUserTeams($id)
    {
        // get all team-member relations of user
        $teams_member = \frontend\models\TeamMember::find()->where(['user_id' => $id])->all();
        // for each relation...
        foreach ($teams_member as $team_member) {
            // ...store team name in an array
            $user_team[] = \frontend\models\Team::find()->where(['id' => $team_member['team_id']])->one();
        }

        return $user_team;
    }
    
    public function addTeam(){
        if ($this->team == null) {
            return;
        }
        $addlist=[];
        $dellist=[];
        $teamlist=TeamMember::find()->where(['user_id'=>$this->id])->all();
        foreach ($teamlist as $oldteam){
            $flag=0;
            foreach($this->team as $i){
                if($oldteam->team_id==$i) $flag=1;
            }
            if($flag==0){
                $dellist[]=$oldteam->team_id;
            }
        }
        foreach ($this->team as $newindex){
            $flag=0;
            foreach($teamlist as $oldteam){
                if($oldteam->team_id==$newindex) $flag=1;
            }
            if($flag==0){
                $addlist[]=$newindex;
            }
        }
        foreach($addlist as $addindex){
            $newteam = new TeamMember(['team_id'=>$addindex,'user_id'=>$this->id]);
            $newteam->save();
        }
        foreach($dellist as $delindex){
            $deltarget=TeamMember::getObjectById($delindex,$this->id);
            $deltarget->delete();
        }
    }
    public function upload()
    {
        if ($this->validate()) {
            if ($this->imageFile == null) return false;
            $this->imageFile->saveAs(Yii::$app->basePath . '/uploads/' . $this->username . '.' . $this->imageFile->extension);
            $this->avatar = $this->username . '.' . $this->imageFile->extension;
            return true;
        } else {
            return false;
        }
    }
    
    public function calPoint($userid,$date){
        $timesheets=Timesheet::find()->where(['user_id'=>$userid])->all();
        $first=date('Y-m-01',strtotime($date));
        $last=date('Y-m-t',strtotime($date));
        $count=0;
        $sumpoint=0;
        foreach ($timesheets as $timesheet){
            if(strtotime($timesheet->date)>=strtotime($first) && strtotime($timesheet->date)<=strtotime($last)){
                if($timesheet->status){
                    $count++;
                    $sumpoint+=$timesheet->point;
                }
            }
        }
        if ($count == 0) return 0;
        return $sumpoint/$count;
    }
    
    public function getRole($id)
    {
        $model = $this->findModel($id);

        foreach (User::$roles as $role) {
            if($model->role == $role['key']) {
                return $role['role'];
            }    
        }
    }

    public function getStatus($id)
    {
        $model = $this->findModel($id);
        
        foreach (User::$status as $_status) {
            if($model->status == $_status['key']) {
                return $_status['status'];
            }
        }
    }
}
