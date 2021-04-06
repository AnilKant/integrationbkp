<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\FruugoRegistration;

/**
 * fruugoRegistrationSearch represents the model behind the search form about `backend\models\fruugoRegistration`.
 */
class FruugoRegistrationSearch extends FruugoRegistration
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
            [['fname', 'lname', 'store_name', 'shipping_source', 'other_shipping_source', 'mobile', 'email', 'annual_revenue', 'reference', 'agreement', 'other_reference', 'country', 'selling_on_fruugo', 'selling_on_fruugo_source', 'other_selling_source', 'approved_by_fruugo'], 'safe'],
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
        $query = FruugoRegistration::find();

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

        $query->andFilterWhere(['like', 'fname', $this->fname])
            ->andFilterWhere(['like', 'lname', $this->lname])
            ->andFilterWhere(['like', 'store_name', $this->store_name])
            ->andFilterWhere(['like', 'shipping_source', $this->shipping_source])
            ->andFilterWhere(['like', 'other_shipping_source', $this->other_shipping_source])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'annual_revenue', $this->annual_revenue])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'agreement', $this->agreement])
            ->andFilterWhere(['like', 'other_reference', $this->other_reference])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'selling_on_fruugo', $this->selling_on_fruugo])
            ->andFilterWhere(['like', 'selling_on_fruugo_source', $this->selling_on_fruugo_source])
            ->andFilterWhere(['like', 'other_selling_source', $this->other_selling_source])
            ->andFilterWhere(['like', 'approved_by_fruugo', $this->approved_by_fruugo]);

        return $dataProvider;
    }
}
