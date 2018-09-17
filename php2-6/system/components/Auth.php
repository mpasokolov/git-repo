<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 16.09.2018
 * Time: 11:43
 */

namespace system\components;

use app\common\models\User;
use system\components\App;


class Auth extends BaseObject {
  public  function checkAuth() {
    if (!User::alreadyLoggedId() && !User::checkAuthWithCookie()) {
      App::$current->request->redirect('lk/login');
    }

    $userId = $_SESSION['user'] ? $_SESSION['user']['id'] : false;
    $userRole = User::getUserRole($userId);

    if ($userRole !== 'admin') {
      App::$current->request->redirect('site/index');
    }
  }
}