<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PricingPlan;

/**
 * PricingPlanSearch represents the model behind the search form about `common\models\PricingPlan`.
 */
class PricingPlanSearch extends PricingPlan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'base_price', 'special_price'], 'integer'],
            [['plan_name', 'plan_type', 'duration', 'plan_status', 'additional_condition'], 'safe'],
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
        $query = PricingPlan::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'base_price' => $this->base_price,
            'special_price' => $this->special_price,
            'apply_on' => $this->apply_on,
        ]);

        $query->andFilterWhere(['like', 'plan_name', $this->plan_name])
            ->andFilterWhere(['like', 'plan_type', $this->plan_type])
            ->andFilterWhere(['like', 'duration', $this->duration])
            ->andFilterWhere(['like', 'plan_status', $this->plan_status])
            ->andFilterWhere(['like', 'additional_condition', $this->additional_condition]);

        return $dataProvider;
    }
}
