<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ShopErasureData;

/**
 * ShopErasureDataSearch represents the model behind the search form about `frontend\modules\etsy\models\ShopErasureData`.
 */
class OldShopErasureDataSearch extends ShopErasureData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'total_products', 'total_orders', 'total_revenue'], 'integer'],
            [['shop_url', 'mobile', 'email', 'marketplace', 'config_set', 'purchased_status', 'install_date', 'uninstall_date', 'last_purchased_plan', 'shopify_plan_name', 'shop_data', 'marketplace_configuration', 'token', 'expiry_date'], 'safe'],
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
        $query = OldShopErasureData::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['merchant_id' => SORT_DESC]]
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
            'total_products' => $this->total_products,
            'total_orders' => $this->total_orders,
            'total_revenue' => $this->total_revenue,
            'install_date' => $this->install_date,
            'uninstall_date' => $this->uninstall_date,
            'expiry_date' => $this->expiry_date,
        ]);

        $query->andFilterWhere(['like', 'shop_url', $this->shop_url])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'marketplace', $this->marketplace])
            ->andFilterWhere(['like', 'config_set', $this->config_set])
            ->andFilterWhere(['like', 'purchased_status', $this->purchased_status])
            ->andFilterWhere(['like', 'last_purchased_plan', $this->last_purchased_plan])
            ->andFilterWhere(['like', 'shopify_plan_name', $this->shopify_plan_name])
            ->andFilterWhere(['like', 'shop_data', $this->shop_data])
            ->andFilterWhere(['like', 'marketplace_configuration', $this->marketplace_configuration])
            ->andFilterWhere(['like', 'token', $this->token]);

        return $dataProvider;
    }
}
