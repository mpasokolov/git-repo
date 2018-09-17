<?php

namespace app\frontend\controllers;

use system\components\Controller;

class SiteController extends Controller {

    //public $layout = 'guest';

    /**
     * 'site/index' action handler
     */
    public function actionIndex() {
        // render Twig template or JSON (with AJAX checking by Controller)
        $this->render('index', [
            'message' => 'Добро пожаловать в наш магазин!',
        ]);
    }

}