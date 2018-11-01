<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 27/10/2018
 * Time: 22:04
 */

namespace app\models;

use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

class User extends ActiveRecord implements IdentityInterface {

    const GENERATE_PASSWORD = 'generatePassword';

    public $authKey;
    public $new_password;

    public static function tableName() {
        return 'user';
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ]
        ];
    }

    public function rules() {
        return [
            [['username', 'password', 'email'], 'required', 'message' => 'Пожалуйста, заполните поле!'],
            [['username', 'password', 'email', 'auth_key', 'access_token'], 'string', 'message' => 'Некорректные данные'],
            ['email', 'email'],
            ['username', 'unique', 'targetAttribute' => 'username', 'message' => 'Логин уже занят'],
            ['email', 'unique', 'targetAttribute' => 'email', 'message' => 'Данный email уже зарегестрирован'],
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('Не поддерживается');
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $authKey === $this -> getAuthKey();
    }

    static public function findByUserName($username) {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password) {
        return \Yii::$app -> security -> validatePassword($password, $this->password);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->password = \Yii::$app->security->generatePasswordHash($this->password);
            return true;
        }
        return false;
    }

    public function getActivities() {
        return $this->hasMany(Activity::class, ['id' => 'id_activity'])->viaTable('Links', ['id_user' => 'id']);
    }

    public function generatePassword() {
        $this->on(self::GENERATE_PASSWORD, function($event) {
            $this->new_password = \Yii::$app->security->generateRandomString();
        });
    }
}