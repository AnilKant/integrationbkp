<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Callschedule;

/**
 * CallscheduleSearch represents the model behind the search form about `backend\models\Callschedule`.
 */
class CallscheduleSearch extends Callschedule
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'no_of_request'], 'integer'],
            [['number', 'shop_url', 'marketplace', 'status', 'time', 'time_zone', 'preferred_timeslot', 'response'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Callschedule::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['time'=>SORT_DESC]]
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
            'merchant_id' => $this->merchant_id,
            'time' => $this->time,
            'no_of_request' => $this->no_of_request,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'shop_url', $this->shop_url])
            ->andFilterWhere(['like', 'marketplace', $this->marketplace])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'time_zone', $this->time_zone])
            ->andFilterWhere(['like', 'preferred_timeslot', $this->preferred_timeslot])
            ->andFilterWhere(['like', 'response', $this->response]);

        return $dataProvider;
    }
}
