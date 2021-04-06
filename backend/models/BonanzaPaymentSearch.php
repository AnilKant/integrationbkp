<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BonanzaPayment;

/**
 * BonanzaPaymentSearch represents the model behind the search form about `backend\models\BonanzaPayment`.
 */
class BonanzaPaymentSearch extends BonanzaPayment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'charge_id', 'merchant_id'], 'integer'],
            [['billing_on', 'activated_on', 'plan_type', 'status', 'recurring_data'], 'safe'],
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
        $query = BonanzaPayment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>  ['pageSize' => isset($params['per-page']) ? intval($params['per-page']) : 50],
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
            'charge_id' => $this->charge_id,
            'merchant_id' => $this->merchant_id,
            'billing_on' => $this->billing_on,
            'activated_on' => $this->activated_on,
        ]);

        $query->andFilterWhere(['like', 'plan_type', $this->plan_type])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'recurring_data', $this->recurring_data]);

        return $dataProvider;
    }
}
