<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2019-01-09
 * Time: 20:15
 */

namespace common\models;


use Codeception\Subscriber\Dependencies;
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;

class TasksSearch extends Tasks {

    public $extra;

    public static function tableName() {
        return 'tasks';
    }

    public function attributeLabels() {
        return [
            'extra' => 'Дополнительные параметры поиска:'
        ];
    }

    public function rules() {
        return [
            [['name', 'description', 'id_admin', 'id_user', 'finish', 'id_team', 'finish_time'], 'safe'],
            [['deadline'], 'match',
                'pattern' => '/^\d{4}(-\d{2})?(-\d{2})?$/',
                'message' => 'Дата должна быть в формате Y-m-d'
            ],
            [['extra', 'id'], 'safe']
        ];
    }

    public function scenarios() {
        return parent ::scenarios();
    }


    public function searchByUser($params) {
        $query = Tasks::find()
            -> where(['id_user' => \Yii::$app -> user -> id])
            -> joinWith('users')
            -> joinWith('teams');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ],
        ]);


        $this -> load($params);

        if (!$this -> validate()) {
            return $dataProvider;
        }

        $dataProvider -> sort -> attributes['id_team'] = [
            'asc'  => ['teams.name' => SORT_ASC],
            'desc' => ['teams.name' => SORT_DESC],
        ];

        \Yii::$app -> db -> cache(function () use($dataProvider) {
            return $dataProvider->prepare();
        }, 60 * 60 * 24, new TagDependency(['tags' => 'user_tasks_search_' . \Yii::$app -> user -> id]));

        return $dataProvider;
    }

    public function searchByTeamLead($params) {
        $query = Tasks::find()
            -> joinWith('teams')
            -> joinWith('users')
            -> where(['teams.teamlead' => \Yii::$app -> user -> id]);

        /*
        $query = Teams::find()
            -> joinWith('tasks')
            -> joinWith('users')
            -> where(['teamlead' => \Yii::$app -> user -> id]);
        */
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ],
        ]);

        $this -> load($params);

        if (!$this -> validate()) {
            return $dataProvider;
        }

        $dataProvider -> sort -> attributes['id_team'] = [
            'asc'  => ['teams.name' => SORT_ASC],
            'desc' => ['teams.name' => SORT_DESC],
        ];

        //$query -> andFilterWhere(['like', 'teams.name', $this -> teams -> name]);

        return $dataProvider;
    }

    public function searchByAdmin($params) {
        $query = Tasks ::find()
            -> joinWith('teams t')
            -> joinWith('users u')
            -> joinWith('admins a');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ],

        ]);

        $this -> load($params);

        if (!$this -> validate()) {
            return $dataProvider;
        };

        $query -> andFilterWhere(['tasks.id' => $this -> id]);

        $query -> andFilterWhere(['like', 'tasks.name', $this -> name])
            -> andFilterWhere(['like', 'description', $this -> description])
            -> andFilterWhere(['like', 'a.username', $this -> id_admin])
            -> andFilterWhere(['like', 'u.username', $this -> id_user])
            -> andFilterWhere(['like', 't.name', $this -> id_team])
            -> andFilterWhere(['like', 'finish', $this -> finish]);

        if ($this -> deadline) {
            $filter = $this -> getDateFilterPeriod($this -> deadline);

            $query -> andFilterWhere(['between', 'deadline', $filter['startDay'], $filter['finishDay']]);
        }

        if ($this -> finish_time) {
            $filter = $this -> getDateFilterPeriod($this -> finish_time);
            $query -> andFilterWhere(['between', 'finish_time', $filter['startDay'], $filter['finishDay']]);
        }

        if ($this -> extra === '2') {
            $date = getdate();
            $query -> andFilterWhere(['<', 'deadline', mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']) + 1])
                -> andFilterWhere(['=', 'finish', 0]);
        }


        if ($this -> extra === '1') {
            $date = getdate();
            $nowTime = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
            $sevenDaysAgoTime = $nowTime - 86400 * 6;
            $query -> andFilterWhere(['=', 'finish', '1'])
                -> andFilterWhere(['>=', 'finish_time', $sevenDaysAgoTime]);
        }

        return $dataProvider;
    }

    public function getDateFilterPeriod($date) {
        $dateArr = explode('-', $date);
        $seconds = 86400;
        $filter = [];

        if (!array_key_exists(1, $dateArr)) {
            $days =  date('z', mktime(0, 0, 0, 12, 31, $dateArr[0]));
            $seconds = ($days + 1) * 86400;
        }
        if (array_key_exists(1, $dateArr) && !array_key_exists(2, $dateArr)) {
            $days = cal_days_in_month(CAL_GREGORIAN, $dateArr[1], $dateArr[0]);
            $seconds = $days * 86400;
        }

        $filter['startDay'] = mktime(0, 0, 0, $dateArr[1] ?? 1, $dateArr[2] ?? 1, $dateArr[0]);
        $filter['finishDay'] = $filter['startDay'] + $seconds - 1;

        return $filter;
    }

}