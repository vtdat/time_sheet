<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $address;
    public $telephone;
    public $birthday;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthday'], 'safe'],
            [['address','full_name'], 'string', 'max' => 50],
            ['full_name', 'required'],
            [['telephone'], 'string', 'max' => 20],
            ['username', 'filter', 'filter' => 'trim'],

            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();

            $user->username = $this->username;
            $user->email = $this->email;
            $user->password = $this->password;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->full_name = $this->full_name;
            $user->address = $this->address;
            $user->telephone = $this->telephone;
            $user->birthday = $this->birthday;
            $user->created_at = time();
            $user->updated_at = time();
            
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
