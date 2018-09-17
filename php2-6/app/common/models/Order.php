<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 16.09.2018
 * Time: 20:47
 */

namespace app\common\models;


use system\components\ActiveRecord;
use app\common\models\Catalog;

class Order extends ActiveRecord {
  public function addToCart($id, $quantity = 1) {
    $good = Catalog::findById($id);

    if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

    $cart = $_SESSION['cart'];
    $cartGood = isset($cart[$id]) ? $cart[$id] : [];
    if (empty($cartGood)) {
      $cartGood = ['id' => $good['id'], 'name' => $good['name'], 'price' => $good['price'], 'quantity' => $quantity];
      $cart[$id] = $cartGood;
      $_SESSION['cart'] = $cart;
    } else {
      $cartGood['quantity'] += 1;
      $cartGood['price'] += $good['price'];
      $cart[$id] = $cartGood;
      $_SESSION['cart'] = $cart;
    }
  }

  public function deleteFromCart($id, $quantity = 1) {
    $good = $_SESSION['cart'][$id];
    $goodCount = $good['quantity'];

    if ($goodCount > 1) {
      $good['quantity'] -= $quantity;
      $_SESSION['cart'][$id] = $good;
      return 1;
    } else {
      unset($_SESSION['cart'][$id]);
      return 2;
    }
  }
}