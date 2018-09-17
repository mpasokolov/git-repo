<?php

namespace app\common\models;

use system\components\Model;
use app\common\models\User;


class Login extends Model {
  public function authWithLoginPassword($username, $password, $remember) {

    if (!self::checkLoginAvailable($username)) {
      return false;
    }

    $userData = User::findOne(['login' => $username]);

    $isAuth = 0;

    if($userData){
      if(self::checkPasswordCorrect($password, $userData['password'])){
        $isAuth = 1;
      } else {
        return false;
      }
    }


    if ($remember){
      setcookie('id_user', $userData['id'], time() + 3600 * 24, '/');
      setcookie('cookie_hash', $userData['password'], time() + 3600 * 24, '/');
      setcookie('user_name', $userData['username'], time() + 3600 * 24, '/');
    }

    $userData['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
    $userData['role'] = User::getUserRole($userData['id']);

    var_dump($userData['role']);

    $_SESSION['user'] = $userData;
    return $isAuth;
  }

  private static function checkLoginAvailable($login) {
    $result = User::findOne(['login' => $login]);
    return $result ? true : false;
  }

  private static function checkPasswordCorrect($password, $passwordVerify) {
    return $password === $passwordVerify;
  }
}