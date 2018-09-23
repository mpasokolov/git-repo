<?php

require '../../vendor/autoload.php';
require '/Users/home/Desktop/geekbrains/php lvl2/homework/lesson6/system/bootstrap.php';

use system\components\App;
use app\common\models\Page;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase {

    function setUp() {
        $ENV = 'frontend';

        $config = array_merge(
          include '../../system/config/main.php',
          include '../../system/config/database.php'
        );

        $app = new App($config);
        $app->start($ENV,false);
    }

    public function addDataProvider() {
      return array(
        array(['title' => 'Test page from PHPUnit', 'description' => 'key1', 'keywords' => 'key2', 'content' => 'myContent']),
      );
    }

    /**
     * @dataProvider addDataProvider
     */
    function testCreateNewPage($arr) {
        $page = new Page($arr);

        $page->save();

        $this->assertNotFalse(Page::findOne([
            'title' => 'Teest page from PHPUnit'
        ]));

        $page->delete();
    }
}
