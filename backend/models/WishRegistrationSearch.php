<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WishRegistration;

/**
 * WishRegistrationSearch represents the model behind the search form about `backend\models\WishRegistration`.
 */
class WishRegistrationSearch extends WishRegistration
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'mobile', 'agreement'], 'integer'],
            [['fname', 'lname', 'email', 'country', 'time_zone', 'time_slot', 'reference', 'other_reference', 'shipping_source', 'other_shipping_source', 'created_at'], 'safe'],
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
        $query = WishRegistration::find();

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
            'mobile' => $this->mobile,
            'agreement' => $this->agreement,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'fname', $this->fname])
            ->andFilterWhere(['like', 'lname', $this->lname])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'time_zone', $this->time_zone])
            ->andFilterWhere(['like', 'time_slot', $this->time_slot])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'other_reference', $this->other_reference])
            ->andFilterWhere(['like', 'shipping_source', $this->shipping_source])
            ->andFilterWhere(['like', 'other_shipping_source', $this->other_shipping_source]);

        return $dataProvider;
    }
}
