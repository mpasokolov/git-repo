<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Activity;

/**
 * ActivitySearch represents the model behind the search form of `app\models\Activity`.
 */
class ActivitySearch extends Activity
{
    public $username;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'start_day', 'end_day', 'is_repeat', 'is_block', 'created_at', 'updated_at'], 'integer'],
            [['title', 'body', 'users'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Activity::find()->innerJoinWith('users', true);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['username', 'id', 'start_day', 'end_day', 'is_repeat', 'is_block', 'title', 'body']]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'start_day' => $this->start_day,
            'end_day' => $this->end_day,
            'is_repeat' => $this->is_repeat,
            'is_block' => $this->is_block,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'username', $this->username]);

        return $dataProvider;
    }

    public function searchByUser($params)
    {
        //$sql = 'SELECT a.* FROM links l INNER JOIN activity a on a.id=l.id_activity WHERE l.id_user=:id';
        //$query = User::findBySql($sql, [':id' => \Yii::$app->user->id]);
        //$query = User::findOne(['id' => \Yii::$app->user->id]) -> activities;
        $query = User::find()
            ->select('activity.*')
            ->from('links')
            ->innerJoin('activity', 'activity.id = links.id_activity')
            ->where(['links.id_user' => \Yii::$app->user->id]);

        // add conditions that should always apply here

       // var_dump($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        //var_dump($this);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'start_day', $this->start_day])
            ->andFilterWhere(['like', 'end_day', $this->end_day])
            ->andFilterWhere(['like', 'is_repeat', $this->is_repeat])
            ->andFilterWhere(['like', 'is_block', $this->is_block])
            ->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }
}
