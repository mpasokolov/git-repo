<?php

namespace app\modules\lk\controllers;

use app\models\Activity;
use app\models\User;
use yii\caching\TagDependency;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `lk` module
 */
class MainController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $data = User::getDb()->cache(function () {
            return User::findOne(['id' => \Yii::$app->user->id]);
        }, 60 * 60 * 24, new TagDependency(['tags' => 'userInfo']));

        return $this->render('index', ['data' => $data]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            $subject = 'Изменение данных';
            $body = 'Данные успешно изменены';
            \Yii::$app->mailer->compose()
                ->setTo($model->email)
                ->setFrom([$model->email => $model->username])
                ->setSubject($subject)
                ->setTextBody($body)
                ->send();

            TagDependency::invalidate(\Yii::$app->cache, 'userInfo');
            return $this->goBack();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
