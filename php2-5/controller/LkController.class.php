<?php

class LkController extends Controller {
    public $view = 'lk';
    public $title;

    function __construct() {
      parent::__construct();
      $this->title .= ' | Личный кабинет';
    }
    function index($data){
      if (!Lk::alreadyLoggedId() && !Lk::checkAuthWithCookie()){
        header('Location: login');
      }

      if (!empty($_POST['exit'])) {
        Lk::exit();
      }

      $userName  = Lk::getUserName();
      return ['user_name' => $userName];
    }

    function login() {
      if (Lk::alreadyLoggedId()) {
        header('Location: \lk');
      }

      if (Lk::checkAuthWithCookie()) {
        header('Location: \lk');
      }

      if (!empty($_POST['login']) && !empty($_POST['password'])) {
        $result = Lk::validate();
        if (is_array($result)) {
          return $result;
        } else {
          header('Location: \lk');
        }
      }
    }
}
