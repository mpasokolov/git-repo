<?php

namespace app\common\models;

use system\components\ActiveRecord;
use system\components\App;
use app\common\models\User_has_role;
use app\common\models\User_role;

class User extends ActiveRecord {

  public static function alreadyLoggedId() {
    if (!isset($_SESSION['user'])) { return false; }
    if ($_SESSION['user']['HTTP_USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT']) {
      session_unset();
      return false;
    }
    return true;
  }

  public static function checkAuthWithCookie() {
    if (isset($_COOKIE['id_user']) && isset($_COOKIE['cookie_hash'])) {
      $userId = $_COOKIE['id_user'];

      $userData = self::findById($userId);

      if(($userData['password'] !== $_COOKIE['cookie_hash']) || ($userData['id'] !== $_COOKIE['id_user'])){
        setcookie('id_user', '', time() - 3600 * 24, '/');
        setcookie('cookie_hash', '', time() - 3600 * 24, '/');
      } else {
        App::$current -> request -> redirect('lk');
      }
    }
    return false;
  }

  public static function exit() {
    session_destroy();
    setcookie('id_user', '', time() - 3600, '/');
    setcookie('cookie_hash', '', time() - 3600, '/');
    setcookie('user_name', '', time() - 3600, '/');
    App::$current -> request -> redirect('lk/login');
  }

  public static function getUserName() {
    $result = $_SESSION['user']['username'] or $_COOKIE['user_name'];
    return $result;
  }

  public static function getUserRole($id) {
    if (!$id) { return false; };

    $userRoleId = User_has_role::findOne(['user_id' => $id]);

    $role = User_role::findById($userRoleId['user_role_id']);

    return $role['name'];
  }
}