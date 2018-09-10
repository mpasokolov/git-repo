<?php

class Lk extends Model {
  private static $error = [];

  public static function validate() {

    if(self::authWithLoginPassword()){
      return true;
    } else {
      return self::$error;
    }
  }

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
      $sql = "SELECT id_user, user_name, user_password FROM user WHERE id_user=:id_user";
      $userData = db::getInstance() -> select($sql, ['id_user' => $userId])[0];

      if(($userData['user_password'] !== $_COOKIE['cookie_hash']) || ($userData['id_user'] !== $_COOKIE['id_user'])){
        setcookie('id_user', '', time() - 3600 * 24, '/');
        setcookie('cookie_hash', '', time() - 3600 * 24, '/');
      } else {
        header('Location: /lk');
      }
    }
    return false;
  }

  private static function authWithLoginPassword() {
    $username = $_POST['login'];
    $password = $_POST['password'];

    if (!self::checkLoginAvailable($username)) {
      self::$error = ['login_error' => 'Данного пользователя не существует'];
      return false;
    }

    $sql = "SELECT id_user, user_name, user_password FROM user WHERE user_login=:user_login";
    $userData = db::getInstance() -> select($sql, ['user_login' => $username])[0];

    $isAuth = 0;

    if($userData){
      if(self::checkPasswordCorrect($password, $userData['user_password'])){
        $isAuth = 1;
      } else {
        self::$error = ['password_error' => 'Пароль неверный'];
        return false;
      }
    }

    if (isset($_POST['rememberMe'])){
      setcookie('id_user', $userData['id_user'], time() + 3600 * 24, '/');
      setcookie('cookie_hash', $userData['user_password'], time() + 3600 * 24, '/');
      setcookie('user_name', $userData['user_name'], time() + 3600 * 24, '/');
    }

    $userData['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

    $_SESSION['user'] = $userData;
    return $isAuth;
  }

  private static function checkLoginAvailable($login) {
    $sql = "SELECT * FROM user WHERE user_login=:login";
    return !empty(db::getInstance() -> Select($sql, ['login' => $login])) ? true : false;
  }

  private static function checkPasswordCorrect($password, $passwordVerify) {
    return $password === $passwordVerify;
  }

  public static function exit() {
    session_destroy();
    setcookie('id_user', '', time() - 3600, '/');
    setcookie('cookie_hash', '', time() - 3600, '/');
    setcookie('user_name', '', time() - 3600, '/');
    header('Location: /lk/login');
  }

  public static function getUserName() {
    $result = $_SESSION['user']['user_name'] or $_COOKIE['user_name'];
    return $result;
  }
}