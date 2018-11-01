<?php
/**
 * Created by PhpStorm.
 * UserDefault: pc
 * Date: 27/10/2018
 * Time: 20:58
 */

namespace app\models;

use yii\base\Model;

class CreateUserForm extends Model {
    public $username;
    public $password;
    public $password_repeat;
    public $email;

    public function rules() {
        return [
            //[['username', 'password', 'email', 'password_repeat'],'required', 'message' => 'Пожалуйста, заполните поле!'],
            //[['username', 'password', 'email', 'password_repeat'], 'string', 'message' => 'Некорректные данные'],

            [['username', 'email'],'required', 'message' => 'Пожалуйста, заполните поле!'],
            [['username', 'email'], 'string', 'message' => 'Некорректные данные'],

            //['password', 'compare', 'compareAttribute' => 'password_repeat', 'message' => 'Пароли не совпадают'],
            ['email', 'email'],
            ['username', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username', 'message' => 'Логин уже занят'],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email', 'message' => 'Данный email уже зарегестрирован'],
        ];
    }

    public function attributeLabels() {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
            'email' => 'Email',
        ];
    }

    public function signUp()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();

        $user -> generatePassword();

        if (!$user->password) {
            $user->trigger(User::GENERATE_PASSWORD);
            $user->password = $user->new_password;
        } else {
            $user->password = $this->password;
        }

        $user->username = $this->username;
        $user->email = $this->email;
        return $user->save() ? $user : null;
    }
}