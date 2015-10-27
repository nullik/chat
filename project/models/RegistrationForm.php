<?php

namespace app\models;

use Yii;
use \yii\base\Model;

class RegistrationForm extends Model
{
    public $username;
    public $password;
    public $confirmPassword;
    public $email;

    public function rules()
    {
        return [
            [['username', 'email'], 'filter', 'filter' => function($value) { return str_replace(' ', '', $value); }],
            ['username', 'match', 'pattern' => '/^[a-z]\w*$/i'],
            [['username', 'password', 'confirmPassword', 'email'], 'required'],
            [['username'], 'string', 'max' => 25],
            [['password'], 'string', 'max' => 40],
            ['email', 'email'],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password'],
            [
                ['username', 'email'],
                'unique',
                'targetClass' => User::className()
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
            'confirmPassword' => 'Confirm password',
            'email' => 'Email'
        ];
    }

    public function register()
    {
        if ($this->validate()) {
            $newUser = new User();
            $newUser->username = $this->username;
            $newUser->passwordHash = hash('md5', $this->password);
            $newUser->email = $this->email;
            $newUser->groupId = 1;
            $newUser->save();
            return true;
        }

        return false;
    }
}

?>
