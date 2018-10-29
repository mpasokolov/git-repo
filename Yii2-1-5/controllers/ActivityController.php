<?php
namespace app\controllers;

use app\models\Activity;
use app\models\CreateActivityForm;
use app\models\Links;
use yii\web\Controller;
use yii\web\UploadedFile;

class ActivityController extends Controller
{
    public function actionIndex() {
        $model = new Activity('Тестовое событие', time(), time() + (7 * 24 * 60 * 60), 1, 'Тут должен быть текст события');
        return $this->render('index', ['model' => $model]);
    }

    public function actionCreate() {
        $model = new CreateActivityForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model -> activityFiles = UploadedFile::getInstances($model, 'activityFiles');
            $date = date(\Yii::$app -> params['dateFormat']);
            if ($model -> upload($date) && $model -> insertActivity()) {
                return $this->goHome();

            }
            return $this -> render('create', ['model' => $model]);
        } else {
            return $this -> render('create', ['model' => $model]);
        }
    }

    public function actionEdit() {
        return $this -> render('edit');
    }

    public static function actionGetActivity($id) {
        return ['name' => 'Имя события', 'body' => 'Текст события', 'start' => 'Начало события', 'Конец события' => 'Конец события'];
    }
}