<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MagentoExtensionDetail;

/**
 * MagentoExtensionDetailSearch represents the model behind the search form about `backend\models\MagentoExtensionDetail`.
 */
class MagentoExtensionDetailSearch extends MagentoExtensionDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['store_url', 'email', 'total_product', 'published', 'unpublished', 'total_order', 'complete_orders', 'config_set','pilot_seller', 'plateform','ac_details','updated_at','last_response','totalRevenue'], 'safe'],
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
        $query = MagentoExtensionDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
        ]);

        $query->andFilterWhere(['like', 'store_url', $this->store_url])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'total_product', $this->total_product])
            ->andFilterWhere(['like', 'published', $this->published])
            ->andFilterWhere(['like', 'unpublished', $this->unpublished])
            ->andFilterWhere(['like', 'total_order', $this->total_order])
            ->andFilterWhere(['like', 'complete_orders', $this->complete_orders])
            ->andFilterWhere(['like', 'config_set', $this->config_set])
            ->andFilterWhere(['like', 'pilot_seller', $this->pilot_seller])
            ->andFilterWhere(['like', 'plateform', $this->plateform])
            ->andFilterWhere(['like', 'ac_details', $this->ac_details]);

        return $dataProvider;
    }
}
