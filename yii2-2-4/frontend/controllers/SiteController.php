<?php

namespace frontend\controllers;

use common\models\Tasks;
use common\models\User;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use common\models\LoginForm;

class SiteController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }

    /**
     * Displays registration page
     *
     * @return string
     */
    public function actionRegistration() {
        $user = new User();

        if ($user -> load(\Yii::$app -> request -> post()) && $user -> save()) {
            $message = 'Вы успешно зарегистрировались, ' . '<a href="' .  Url::to('login') . '"> Авторизоваться</a>';
            \Yii::$app -> session -> setFlash('success', $message);

            $guestRole = \Yii::$app -> authManager -> getRole('guest');
            \Yii::$app -> authManager -> assign($guestRole, $user -> id);
            return $this -> refresh();
        }

        return $this -> render('registration', ['model' => $user]);
    }

    public function actionRegistrationValidate() {
        $user = new User();

        if (Yii::$app -> request -> isAjax && $user -> load(Yii::$app->request->post()))  {
            Yii::$app -> response -> format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }
    }

    public function actionTest() {
        $obj1 = new \stdClass();
        $obj1 -> id = 1;
        $obj2 = new \stdClass();
        $obj2 -> id = 2;
        $obj3 = new \stdClass();
        $obj3 -> id = 3;
        $array = [
            $obj1,
            $obj2,
            $obj3
        ];

        $arr = array_filter($array, function ($conn) { return $conn -> id == 2;});
        $key = key($arr);

        array_splice($array, $key, 1);

        //$query = Teams::find() -> joinWith('teams') -> asArray() -> all()[0];

        return $this ->render('test', ['key' => $key, 'arr' => $array]);
    }
}
