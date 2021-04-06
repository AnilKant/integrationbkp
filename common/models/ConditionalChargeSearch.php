<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ConditionalCharge;
use common\models\ConditionalRange;

/**
 * ConditionalChargeSearch represents the model behind the search form about `common\models\ConditionalCharge`.
 */
class ConditionalChargeSearch extends ConditionalCharge
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['charge_name', 'charge_condition', 'charge_range', 'merchant_base', 'charge_type', 'apply'], 'safe'],
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
        $query = ConditionalCharge::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['conditional_range']);
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'charge_name', $this->charge_name])
            ->andFilterWhere(['like', 'charge_condition', $this->charge_condition])
            ->andFilterWhere(['like', 'charge_range', $this->charge_range])
            ->andFilterWhere(['like', 'merchant_base', $this->merchant_base])
            ->andFilterWhere(['like', 'charge_type', $this->charge_type])
            ->andFilterWhere(['like', 'apply', $this->apply]);

        return $dataProvider;
    }
}
