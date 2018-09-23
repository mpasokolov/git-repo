<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 20.09.2018
 * Time: 19:11
 */

namespace app\backend\controllers;
use system\components\Controller;


class TestsController extends Controller {
    public function actionIndex() {
      $this -> render('index', []);
    }

    public function actionDo() {
      $results = [];
      exec('../../vendor/bin/phpunit ../../system/tests/LoginTest.php --log-junit ../../system/tests/logLogin.xml');
      if (file_exists('../../system/tests/logLogin.xml')) {
        $login = simplexml_load_file('../../system/tests/logLogin.xml');
        $loginDecode = json_decode(json_encode($login -> testsuite -> testsuite), true)['@attributes'];
        array_push($results, ['name' => $loginDecode['name'], 'assertions' => $loginDecode['assertions'],
          'errors' => $loginDecode['errors'], 'failures' => $loginDecode['failures'], 'skipped' => $loginDecode['skipped'],
          'time' => $loginDecode['time']]);
      }

      exec('../../vendor/bin/phpunit ../../system/tests/PageTest.php --log-junit ../../system/tests/LogPage.xml');
      if (file_exists('../../system/tests/LogPage.xml')) {
        $page = simplexml_load_file('../../system/tests/LogPage.xml');
        $pageDecode = json_decode(json_encode($page -> testsuite -> testsuite), true)['@attributes'];
        array_push($results, ['name' => $pageDecode['name'], 'assertions' => $pageDecode['assertions'],
          'errors' => $pageDecode['errors'], 'failures' => $pageDecode['failures'], 'skipped' => $pageDecode['skipped'],
          'time' => $pageDecode['time']]);
      }

      $data['response_json'] = ['result' => 1, 'data' => $results];
      $this -> renderJson($data['response_json']);
    }
}