<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BonanzaRegistration;

/**
 * bonanzaRegistrationSearch represents the model behind the search form about `backend\models\bonanzaRegistration`.
 */
class BonanzaRegistrationSearch extends BonanzaRegistration
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
            [['fname', 'lname', 'store_name', 'mobile', 'email', 'reference', 'agreement', 'other_reference', 'selling_on_bonanza', 'selling_on_bonanza_source', 'other_selling_source'], 'safe'],
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
        $query = BonanzaRegistration::find();

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
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'agreement', $this->agreement])
            ->andFilterWhere(['like', 'other_reference', $this->other_reference])
            ->andFilterWhere(['like', 'selling_on_bonanza', $this->selling_on_bonanza])
            ->andFilterWhere(['like', 'selling_on_bonanza_source', $this->selling_on_bonanza_source])
            ->andFilterWhere(['like', 'other_selling_source', $this->other_selling_source]);

        return $dataProvider;
    }
}
