<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WalmartClient;

/**
 * WalmartClientSearch represents the model behind the search form about `backend\models\WalmartClient`.
 */
class WalmartClientSearch extends WalmartClient
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'code', 'is_activated'], 'integer'],
            [['firstname', 'lastname', 'seller_store_name', 'email', 'phone', 'country', 'annual_revenue', 'website', 'shipping_source', 'total_skus', 'company_address', 'valid_tax_w9', 'warehouse_in_usa', 'type_product', 'selling_marketplace', 'different_channel_partner', 'others', 'walmart_contact_before', 'walmart_approved', 'amazon_sellerurl', 'company_name', 'other_framework', 'integration_framework', 'position'], 'safe'],
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
        $query = WalmartClient::find();

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
            'code' => $this->code,
            'is_activated' => $this->is_activated,
        ]);

        $query->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'seller_store_name', $this->seller_store_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'annual_revenue', $this->annual_revenue])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'shipping_source', $this->shipping_source])
            ->andFilterWhere(['like', 'total_skus', $this->total_skus])
            ->andFilterWhere(['like', 'company_address', $this->company_address])
            ->andFilterWhere(['like', 'valid_tax_w9', $this->valid_tax_w9])
            ->andFilterWhere(['like', 'warehouse_in_usa', $this->warehouse_in_usa])
            ->andFilterWhere(['like', 'type_product', $this->type_product])
            ->andFilterWhere(['like', 'selling_marketplace', $this->selling_marketplace])
            ->andFilterWhere(['like', 'different_channel_partner', $this->different_channel_partner])
            ->andFilterWhere(['like', 'others', $this->others])
            ->andFilterWhere(['like', 'walmart_contact_before', $this->walmart_contact_before])
            ->andFilterWhere(['like', 'walmart_approved', $this->walmart_approved])
            ->andFilterWhere(['like', 'amazon_sellerurl', $this->amazon_sellerurl])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'other_framework', $this->other_framework])
            ->andFilterWhere(['like', 'integration_framework', $this->integration_framework])
            ->andFilterWhere(['like', 'position', $this->position]);

        return $dataProvider;
    }
}
