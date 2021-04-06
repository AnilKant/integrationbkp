<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WalmartRecurringPayment;

/**
 * WalmartRecurringPaymentSearch represents the model behind the search form about `app\models\WalmartRecurringPayment`.
 */
class WalmartRecurringPaymentSearch extends WalmartRecurringPayment
{
    /**
     * @inheritdoc
     */
    public $expire_date;
    public $expire_date2;
     public $shop_name;
     public $walmartExtensionDetail;
    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
            [['billing_on', 'activated_on', 'plan_type', 'status', 'recurring_data','expire_date','expire_date2','shop_name'], 'safe'],
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
        $query = WalmartRecurringPayment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
         $dataProvider->sort->attributes['walmartExtensionDetail'] = [
           
            'asc' => ['walmart_extension_detail.expire_date' => SORT_ASC],
            'desc' => ['walmart_extension_detail.expire_date' => SORT_DESC],
        ];
       
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['walmartExtensionDetail']);
        $query->andFilterWhere([
            'walmart_recurring_payment.id' => $this->id,
            'walmart_recurring_payment.merchant_id' => $this->merchant_id,
            'walmart_recurring_payment.billing_on' => $this->billing_on,
            'walmart_recurring_payment.activated_on' => $this->activated_on,
        ]);

        $query->andFilterWhere(['=', 'plan_type', $this->plan_type])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'recurring_data', $this->recurring_data])
            //->andFilterWhere(['like', 'walmart_shop_details.shop_name', $this->shop_name])
            ->andFilterWhere(['>=','walmart_extension_detail.expire_date',$this->expire_date])
            ->andFilterWhere(['<=','walmart_extension_detail.expire_date',$this->expire_date2]);

        return $dataProvider;
    }
}
