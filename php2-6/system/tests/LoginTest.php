<?php

require '../bootstrap.php';
require '../../vendor/autoload.php';
require '../../app/common/models/Login.php';


use app\common\models\Login;
use PHPUnit\Framework\TestCase;
use system\components\App;

define('ENV', 'frontend');


class LoginTest extends TestCase
{
  protected $fixture;

  protected function setUp()
  {
    $this->fixture = new Login();
  }

  protected function tearDown()
  {
    $this->fixture = NULL;
  }

  public function addDataProvider() {
    return array(
      array('1','2','3', false),
      array('mpasokolov@gmail.com', 'pomnhl63', false),
      array('-1','-1', false),
    );
  }
  
  /**
   * @dataProvider addDataProvider
   */
  public function testAuthWithLoginPassword()
  {
    $config = array_merge(
      include '../../system/config/main.php',
      include '../../system/config/database.php'
    );

    $app = new App($config);
    $app->start();

    $login = $this -> fixture;
    $this -> assertEquals('1', $login -> AuthWithLoginPassword('mpasokolov@gmail.com', 'pomnhl63', false));
  }
}
