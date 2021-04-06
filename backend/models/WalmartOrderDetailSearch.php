<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\walmart\models\WalmartOrderDetail;

/**
 * WalmartOrderDetailsSearch represents the model behind the search form about `frontend\modules\walmart\models\WalmartOrderDetails`.
 */
class WalmartOrderDetailSearch extends WalmartOrderDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'shopify_order_id'], 'integer'],
            [['shopify_order_name','order_total', 'sku', 'purchase_order_id', 'order_data', 'shipment_data', 'created_at', 'status'], 'safe'],
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
        $query = WalmartOrderDetail::find()->select('id,count(purchase_order_id) as purchase_order_id,merchant_id,shopify_order_name,SUM(order_total) as order_total')/*->where(['merchant_id' => MERCHANT_ID])*/;

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

        ]);

        $query->andFilterWhere(['like', 'shopify_order_name', $this->shopify_order_name])
            ->andFilterWhere(['like', 'purchase_order_id', $this->purchase_order_id])->orderBy(['purchase_order_id' => SORT_DESC])
            ->andFilterWhere(['like', 'order_total', $this->order_total])->orderBy(['order_total' => SORT_DESC]);
        $query->groupBy(['walmart_order_details.merchant_id']);

//        var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();

        return $dataProvider;
    }
}
