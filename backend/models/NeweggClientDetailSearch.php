<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\NeweggClientDetail;

/**
 * NeweggClientDetailSearch represents the model behind the search form about `backend\models\NeweggClientDetail`.
 */
class NeweggClientDetailSearch extends NeweggClientDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
            [['name', 'shipping_source', 'other_shipping_source', 'mobile', 'email', 'reference', 'agreement', 'other_reference'], 'safe'],
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
        $query = NeweggClientDetail::find();

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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'shipping_source', $this->shipping_source])
            ->andFilterWhere(['like', 'other_shipping_source', $this->other_shipping_source])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'agreement', $this->agreement])
            ->andFilterWhere(['like', 'other_reference', $this->other_reference]);

        return $dataProvider;
    }
}
