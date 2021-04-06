<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\JetRecurringPayment;

/**
 * JetRecurringPaymentSearch represents the model behind the search form about `backend\models\JetRecurringPayment`.
 */
class JetRecurringPaymentSearch extends JetRecurringPayment
{
    /**
     * @inheritdoc
     */
    public $shop_name;
    public $expired_on;
    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
            [['billing_on', 'activated_on', 'plan_type', 'status', 'recurring_data','shop_name','expired_on'], 'safe'],
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
        $query = JetRecurringPayment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['merchant']);
        $query->andFilterWhere([
            'id' => $this->id,
            'merchant_id' => $this->merchant_id,
            'billing_on' => $this->billing_on,
            'activated_on' => $this->activated_on,
        ]);

        $query->andFilterWhere(['like', 'plan_type', $this->plan_type])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'expired_on', $this->expired_on])
            ->andFilterWhere(['like', 'recurring_data', $this->recurring_data]);

        return $dataProvider;
    }
}
