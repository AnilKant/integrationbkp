<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SearsRecurringPayment;

/**
 * SearsRecurringPaymentSearch represents the model behind the search form about `backend\models\SearsRecurringPayment`.
 */
class SearsRecurringPaymentSearch extends SearsRecurringPayment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
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
        $query = SearsRecurringPayment::find();

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
