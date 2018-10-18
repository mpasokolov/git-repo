<?php
namespace app\controllers;

use app\models\Activity;
use yii\web\Controller;

class ActivityController extends Controller
{
    public function actionIndex() {
        $model = new Activity('Тестовое событие', time(), time() + (7 * 24 * 60 * 60), 1, 'Тут должен быть текст события');

        return $this->render('index', ['model' => $model]);
    }

    public function actionCreate() {
        return 'Создать активность';
    }
}