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
	public $email;
	public $expired_on;
    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
            [['billing_on', 'activated_on', 'status','plan_type', 'recurring_data','id', 'email','expired_on', 'expired_on_to', 'expired_on_from', 'activated_on_from', 'activated_on_to'], 'safe'],
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
        /*$query->joinWith ( [
        		'user'
        ] );
        */
        $query->joinWith ( [
                'jet_shop_details'
        ] );
        $dataProvider->sort->attributes ['jet_recurring_payment'] = [
        		'asc' => [ 'jet_shop_details.email' => SORT_ASC ],
        		'desc' => ['jet_shop_details.email' => SORT_DESC ]
        ];
        
        $query->andFilterWhere([
            'jet_recurring_payment.id' => $this->id,
            'jet_recurring_payment.merchant_id' => $this->merchant_id,
            'billing_on' => $this->billing_on,
           // 'activated_on' => $this->activated_on,
        ]);

        if($this->activated_on_from !="" && $this->activated_on_to !=""){
            $query->andFilterWhere(['between', 'activated_on', $this->activated_on_from, $this->activated_on_to]);
        }elseif($this->activated_on_from !=""){
            $query->andFilterWhere(['>=', 'activated_on', $this->activated_on_from]);
        }elseif($this->activated_on_to !=""){
            $query->andFilterWhere(['<=', 'activated_on', $this->activated_on_to]);
        }

        if($this->expired_on_from !="" && $this->expired_on_to !=""){
            $query->andFilterWhere(['between', 'jet_shop_details.expired_on', $this->expired_on_from, $this->expired_on_to]);
        }elseif($this->expired_on_from !=""){
            $query->andFilterWhere(['>=', 'jet_shop_details.expired_on', $this->expired_on_from]);
        }elseif($this->expired_on_to !=""){
            $query->andFilterWhere(['<=', 'jet_shop_details.expired_on', $this->expired_on_to]);
        }
        $query->andFilterWhere(['like', 'jet_recurring_payment.status', $this->status])
        	  ->andFilterWhere(['like', 'plan_type', $this->plan_type])
              ->andFilterWhere(['like', 'recurring_data', $this->recurring_data])
        	  ->andFilterWhere(['like', 'jet_shop_details.email', $this->email]);
        	 // ->andFilterWhere(['between', 'jet_shop_details.expired_on', $this->expired_on_from, $this->expired_on_to]);
              //->andFilterWhere(['like', 'jet_shop_details.expired_on', $this->expired_on])
        //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();   
        return $dataProvider;
    }
}
