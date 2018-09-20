<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 19.09.2018
 * Time: 22:01
 */

define('ENV', 'frontend');

require '../bootstrap.php';
require '../../vendor/autoload.php';
require '../../app/common/models/Login.php';


use app\common\models\Order;
use PHPUnit\Framework\TestCase;
use system\components\App;


class OrderTest extends TestCase
{
  public function testAddToCart()
  {
    $config = array_merge(
      include '../../system/config/main.php',
      include '../../system/config/database.php'
    );

    $app = new App($config);
    $app->start();

    $order = new Order();
    $this -> assertEquals(true, $order -> addToCart(2, 1));
  }
}
