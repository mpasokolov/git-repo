<?php
/**
 * Created by PhpStorm.
 * UserDefault: pc
 * Date: 27/10/2018
 * Time: 20:58
 */

namespace app\models;

use yii\db\ActiveRecord;

class CreateUserForm extends ActiveRecord {
    /**
     * UserDefault login
     * @var string
     */
    public $login;

    /**
     * UserDefault password
     * @var string
     */
    public $pass;

    /**
     * UserDefault email
     * @var string
     */
    public $mail;

    /**
     * Verify password
     * @var string
     */
    public $pass_repeat;

    public function rules() {
        return [
            [['login', 'pass', 'mail', 'pass_repeat'], 'required', 'message' => 'Пожалуйста, заполните поле!'],
            [['login', 'pass', 'mail', 'pass_repeat'], 'string', 'message' => 'Некорректные данные'],
            ['pass', 'compare', 'message' => 'Пароли не совпадают'],
            [['mail'], 'email'],
            ['login', 'unique', 'targetAttribute' => 'username', 'message' => 'Логин уже занят'],
            ['mail', 'unique', 'targetAttribute' => 'email', 'message' => 'Данный email уже зарегестрирован'],
        ];
    }

    public function attributeLabels() {
        return [
            'login' => 'Логин',
            'pass' => 'Пароль',
            'pass_repeat' => 'Повторите пароль',
            'mail' => 'Email',
        ];
    }

    public static function tableName() {
        return 'user';
    }
}