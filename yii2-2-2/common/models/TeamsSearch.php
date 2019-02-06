<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2019-01-11
 * Time: 20:18
 */

namespace common\models;


use yii\data\ActiveDataProvider;

class TeamsSearch extends Teams {
    public $username;

    public static function tableName() {
        return 'teams';
    }

    public function rules() {
        return [
          [['teamlead', 'name', 'id', 'username', 'teams.name'], 'safe']
        ];
    }

    public function search($params) {
        $query = UsersTeams::find() -> where(['id_user' => \Yii::$app -> user -> id])
            -> joinWith('teams.teamLead');

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this -> load($params);

        return $dataProvider;
    }

    public function searchUsers($params, $id) {
        $query = Teams::findOne($id) -> getUsers();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this -> load($params);


        return $dataProvider;
    }
}