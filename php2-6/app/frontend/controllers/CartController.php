<?php

namespace app\frontend\controllers;

use app\common\models\Order;
use system\components\Controller;
use system\components\App;
use app\common\models\User;



class CartController extends Controller
{
  public function actionIndex() {
    if (!User::alreadyLoggedId() && !User::checkAuthWithCookie()) {
      App::$current -> request -> redirect('lk/login');
    }
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $this -> render('index', ['data' => $cart]);
  }
  public function actionBuy() {
    if (!User::alreadyLoggedId() && !User::checkAuthWithCookie()) {
      $data = ['response_json' => ['result' => 2]];
      $this -> renderJson($data['response_json']);
      return false;
    }
    $order = new Order();
    //var_dump(App::$current->request->post());
    if ($order->loadAjax(App::$current->request->post())) {
      $order -> addToCart($order -> id_good, $order -> quantity);
      $data = ['response_json' => ['result' => 1]];
      $this -> renderJson($data['response_json']);
    }
  }

  public function actionDelete() {
    $order = new Order();
    if ($order->loadAjax(App::$current->request->post())) {
      $result = $order -> deleteFromCart($order -> id_good, $order -> quantity);
      if ($result === 1) {
        $data['response_json'] = ['result' => 1];
      } else if ($result === 2) {
        $data['response_json'] = ['result' => 2];
      } else {
        $data['response_json'] = ['result' => 0];
      }
      $this->renderJson($data['response_json']);
    }
  }
}