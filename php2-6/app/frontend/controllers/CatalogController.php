<?php

namespace app\frontend\controllers;
use system\components\Controller;
use app\common\models\Catalog;

class CatalogController extends Controller {

  public function actionIndex() {
    $catalog = Catalog::findAll();
    $this -> render('index', ['catalog' => $catalog]);
  }
}