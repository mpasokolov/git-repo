<?php

require '/Users/home/Desktop/geekbrains/php lvl2/homework/lesson6/system/bootstrap.php';
require '../../vendor/autoload.php';


use app\common\models\Login;
use PHPUnit\Framework\TestCase;
use system\components\App;

class LoginTest extends TestCase
{
  protected $fixture;

  protected function setUp()
  {
    $ENV = 'frontend';

    $this->fixture = new Login();

    $config = array_merge(
      include '../../system/config/main.php',
      include '../../system/config/database.php'
    );

    $app = new App($config);
    $app->start($ENV,false);
  }

  protected function tearDown() {
    $_SESSION = null;
    $_COOKIE = null;
  }


  public function addDataProvider() {
    return array(
      array('mpasokolov@gmail.com', 'test', false),
      //array('1','2','3', false),
      //array('-1','-1', false),
    );
  }

  /**
   * @dataProvider addDataProvider
   */
  public function testAuthWithLoginPassword($a, $b, $flag)
  {
    $this -> assertEquals('1', $this -> fixture -> AuthWithLoginPassword($a, $b, $flag));
  }
}
