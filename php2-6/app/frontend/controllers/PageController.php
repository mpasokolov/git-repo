<?php


namespace app\frontend\controllers;

use app\common\models\Page;
use system\components\Controller;

class PageController extends Controller {

    public function actionIndex() {
        $pages = Page::findAll();

        return $this->render(
            'index', [
                'pages' => $pages,
            ]
        );
    }

    // SQL Injection
    public function actionView($id) {
        $page = Page::findById($id);

        $this->render(
            'view', [
                'page' => $page,
            ]
        );
    }
}