<?php

namespace app\frontend\controllers;

use app\common\models\Catalog;
use system\components\Controller;

class GoodController extends Controller {
  public function actionIndex($id) {
    $page = Catalog::findById($id);
    $this->render('index', ['good' => $page]);
  }
}