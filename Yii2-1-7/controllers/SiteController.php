<?php

namespace app\controllers;

use app\models\Calendar;
use app\models\Activity;
use app\models\CreateUserForm;
use app\models\User;
use Yii;
use yii\bootstrap\Html;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Url;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
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
    public function actionIndex()
    {
        $now = getdate();

        $model = new Calendar();

        if (!\Yii::$app->request->get('day')
            or !\Yii::$app->request->get('year')
            or \Yii::$app->request->get('year') < $now['year']
            or \Yii::$app->request->get('day') > 365
            or \Yii::$app->request->get('day') < 0
            or (\Yii::$app->request->get('year') == $now['year'] && \Yii::$app->request->get('day') <$now['yday'])
            or \Yii::$app->request->get('year') - $now['year'] > 1)
        {
            $data = $model -> getStartDay();
            $day = $data['day'];
            $year = $data['year'];
            $this -> redirect(Url::to(['/site/index', 'day' => $day, 'year' => $year]));
        }

        if (\Yii::$app->request->get('action')) {
            $action = \Yii::$app->request->get('action');
            $day = \Yii::$app->request->get('day');
            $year = \Yii::$app->request->get('year');
            $data = $model -> getStartDay($action, $day, $year);
            $day = $data['day'];
            $year = $data['year'];
            $this -> redirect(Url::to(['/site/index', 'day' => $day, 'year' => $year]));
        }

        $action = \Yii::$app->request->get('action');
        $day = \Yii::$app->request->get('day');
        $year = \Yii::$app->request->get('year');
        $dates = $model->getDates($action, $day, $year);

        return $this -> render('index',
            ['model' => $model, 'dates' => $dates, 'action' => $action, 'day' => $day, 'year' => $year]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->pass = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRegistration()
    {
        $model = new CreateUserForm();

        if ($model->load(\Yii::$app->request->post())) {
            $result = $model->signUp();
            if ($result) {
                $message = $result->new_password ?
                    'Вы успешно зерегистрировались! Ваш пароль: ' . $result->new_password . '! ' .  Html::a('Авторизоваться', ['@web/site/login']) :
                    'Вы успешно зерегистрировались! ' .  Html::a('Авторизоваться', ['@web/site/login']);
                \Yii::$app->session->setFlash('success', $message);
                //return $this -> redirect(Url::to('@web/site/login'));
            }
        }
        return $this->render('registration', ['model' => $model]);
    }

}
