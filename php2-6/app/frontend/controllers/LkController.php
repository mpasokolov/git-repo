<?php
namespace app\frontend\controllers;

use system\components\Controller;
use app\common\models\User;
use app\common\models\Login;
use system\components\App;

class LkController extends Controller {
  function actionIndex(){
    if (!User::alreadyLoggedId() && !User::checkAuthWithCookie()){
      App::$current -> request -> redirect('lk/login');
    }

    if (!empty($_POST['exit'])) {
      user::exit();
    }

    $userId = $_SESSION['user'] ? $_SESSION['user']['id'] : false;

    $userName  = User::getUserName();
    $userRole = User::getUserRole($userId);

    $this -> render('index',
      [
      'user_name' => $userName,
      'user_role' => $userRole,
      ]
    );
  }

  function actionLogin() {

    if (User::alreadyLoggedId() or User::checkAuthWithCookie()) {
      App::$current -> request -> redirect('lk');
    }

    $login = new Login();

    $data = [];


    if($login -> load(App::$current->request->post())) {
      if (!empty($login -> login) && !empty($login -> password)) {
        $remember = $login -> remember ?? null;
        $result = $login -> authWithLoginPassword($login -> login, $login -> password, $remember);

        if ($result === 1) {
          App::$current -> request -> redirect('lk');
        } else {
          $data = ['login' => $login -> login, 'password' => $login -> password, 'error' => $login -> errors];
        }
      }
    }

    $this -> render('login', ['data' => $data]);
  }
}
