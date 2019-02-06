<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2019-01-11
 * Time: 19:24
 */

namespace common\models;


use yii\db\ActiveRecord;

class Teams extends ActiveRecord {
    public function getTeams() {
        return $this -> hasMany(User::class, ['id' => 'id_user'])
            ->viaTable('users_teams', ['id_teaam' => 'id'], function($query) {
                $query -> where(['id_user' => \Yii::$app -> user -> id]);
            });
    }

    public function getUsers() {
        return $this -> hasMany(User::class, ['id' => 'id_user'])
            ->viaTable('users_teams', ['id_team' => 'id']);
    }

    public function getTeamLead() {
        return $this -> hasOne(User::class, ['id' => 'teamlead']);
    }

    public static function checkAccess($idTeam) {
        $team = self::findOne($idTeam);
        return !$team -> teamlead ||$team -> teamlead !== \Yii::$app -> user -> id ? false : true;
    }

    public static function getTeamLeadId($teamId) {
        return self::findOne($teamId) -> teamlead;
    }

    public function getTasks() {
        return $this -> hasMany(Tasks::class, ['id_team' => 'id']);

    }

}