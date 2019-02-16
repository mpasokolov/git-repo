<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2019-01-11
 * Time: 19:24
 */

namespace common\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Teams extends ActiveRecord {
    public function behaviors() {
        return [
            'class' => TimestampBehavior::class,
        ];
    }

    public function attributeLabels() {
        return [
            'name' => 'Название команды',
            'teamlead' => 'Лидер команды',
            'parent_id' => 'Родительский проект'
        ];
    }

    public function rules() {
        return [
            ['name', 'string', 'max' => 50, 'message' => 'Поле не может быть длинее 50 символов'],
            [['teamlead', 'name'], 'required', 'message' => 'Поле обязательно для заполнения'],
            ['name', 'unique',
                'targetClass' => Teams::class,
                'targetAttribute' => 'name',
                'message' => 'Команда с таким именем уже существует'
            ]
        ];
    }

    public function getTeams() {
        return $this -> hasMany(User::class, ['id' => 'id_user'])
            ->viaTable('users_teams', ['id_team' => 'id'], function($query) {
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

    public function getParentTeam() {
        return $this -> hasOne(Teams::class, ['id' => 'parent_id']);
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