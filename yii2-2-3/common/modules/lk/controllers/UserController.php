<?php
namespace common\modules\lk\controllers;


use common\models\Invites;
use common\models\InvitesSerach;
use common\models\TeamsSearch;
use common\models\User;
use common\models\UsersTeams;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

class UserController extends Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex() {
        $userId = \Yii::$app -> user -> id;

        $user = $this -> findModel($userId);

        $teamsSearchModel = new TeamsSearch();
        $teamsDataProvider = $teamsSearchModel -> search(\Yii::$app -> request -> queryParams);

        $inviteSearchModel = new InvitesSerach();;
        $invitesDataProvider = $inviteSearchModel -> search(\Yii::$app -> request -> queryParams);

        return $this -> render('index', [
            'teamsSearchModel' => $teamsSearchModel,
            'teamsDataProvider' => $teamsDataProvider,
            'invitesDataProvider' => $invitesDataProvider,
            'user' => $user
        ]);
    }

    public function actionAccept($team, $invite) {
        $user = new UsersTeams();
        $transaction = UsersTeams::getDb() -> beginTransaction();

        try {
            $user -> id_team = $team;
            $user -> id_user = \Yii::$app -> user -> id;

            $user -> save();

            $currentRole =  \Yii::$app -> authManager -> getRolesByUser(\Yii::$app -> user -> id);
            \Yii::info($currentRole);

            if ($currentRole['name'] === 'guest') {
                $userRole = \Yii::$app -> authManager -> getRole('user');
                $guestRole = \Yii::$app -> authManager -> getRole('guest');
                \Yii::$app -> authManager -> revoke($guestRole, \Yii::$app -> user -> id);
                \Yii::$app -> authManager -> assign($userRole, \Yii::$app -> user -> id);
            }

            Invites::findOne($invite) -> delete();

            $transaction -> commit();
            \Yii::$app -> session ->setFlash('success', 'Приглашение принято!');
        } catch (\Throwable $e) {
            $transaction ->rollBack();
            throw $e;
        }

        return $this -> redirect(Url ::to(['@web/lk/user']));
    }

    public function actionUpdate($id) {
        $user = $this -> findModel($id);

        if ($user -> id !== \Yii::$app -> user -> id) {
            throw new MethodNotAllowedHttpException('У вас нет прав для данного действия');
        }

        if ($user -> load(\Yii::$app -> request -> post()) && $user -> save()) {
            \Yii::$app -> session -> setFlash('success', 'Данные успешно обновлены');
            return $this -> redirect('index');
        }
        return $this -> render('update', ['user' => $user]);
    }

    public function actionReject($id) {
        Invites::findOne($id) -> delete();

        \Yii::$app -> session ->setFlash('success', 'Приглашение отклонено!');
        return $this -> redirect(Url ::to(['@web/lk/user']));
    }

    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Данной страницы не существует');
    }
}