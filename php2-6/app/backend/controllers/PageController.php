<?php

namespace app\backend\controllers;
use app\common\models\Page;
use system\components\App;
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

    public function actionCreate() {
      $page = new Page();

      if ($page->load(App::$current->request->post())) {
          if ($page->save()) {
              //App::$current->request->redirect(
                  //'page/view?id='.$page->id
              //);
          }
      }

      return $this->render(
          'create', [
              'page' => $page,
          ]
      );
    }
}