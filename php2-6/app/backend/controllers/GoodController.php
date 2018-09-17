<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 16.09.2018
 * Time: 12:37
 */

namespace app\backend\controllers;

use app\common\models\Catalog;
use app\common\models\Good;
use system\components\App;
use system\components\Controller;

class GoodController extends Controller
{
  function actionCreate() {
    $page = new Catalog();
    $image = new Good();

    if ($page->load(App::$current->request->post())) {
      if ($page->save()) {
        if ($image -> saveFile($page -> id)) {
          App::$current->request->redirect('admin/catalog');
        }
      }
    }
    $this -> render('create', []);
  }
}