<?php
namespace app\controllers;

use app\models\Activity;
use app\models\CreateActivityForm;
use app\models\EditActivityForm;
use app\models\Links;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\Url;

class ActivityController extends Controller
{
    public function actionIndex() {
        if (\Yii::$app->user->isGuest) {
            $this -> redirect(Url::toRoute('/site/login'));
        }

        $id = \Yii::$app->request->get('id');
        $data = Activity::find()->where(['id' => $id])->asArray()->one();

        $editModel = new EditActivityForm();

        if ($editModel->load(\Yii::$app->request->post()) && $editModel->validate()) {
            $activity = Activity::findOne($id);
            $activity -> title = $editModel -> name;
            $activity -> start_day = strtotime($editModel -> start);
            $activity -> end_day = strtotime($editModel -> end);
            $activity -> is_repeat = $editModel -> repeat;
            $activity -> is_block = $editModel -> block;
            $activity -> body = $editModel -> text;
            if ($activity -> update() !== false) {
                $this -> redirect(Url::to(['activity/index', 'id' => $id]));
            } else {
                return $this->render('edit', ['model' => $editModel, 'data' => $data]);
            }
        }

        if (\Yii::$app->request->get('edit') === '1') {
            $data['start_day'] = date('Y-m-d', $data['start_day']);
            $data['end_day'] = date('Y-m-d', $data['end_day']);
            return $this->render('edit', ['model' => $editModel, 'data' => $data]);
        }

        $activity = new Activity();
        return $this->render('index', ['model' => $activity, 'data' => $data]);
    }

    public function actionCreate() {

        if (\Yii::$app->user->isGuest) {
            $this -> redirect(Url::toRoute('/site/login'));
        }
        $model = new CreateActivityForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model -> activityFiles = UploadedFile::getInstances($model, 'activityFiles');
            $date = date(\Yii::$app -> params['dateFormat']);
            if ($model -> upload($date) && $model -> createActivity()) {
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

    public function actionTest() {
        $data = Activity::find()->joinWith('users')->all();
        var_dump($data);
    }
}