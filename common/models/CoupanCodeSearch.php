<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CoupanCode;

/**
 * CoupanCodeSearch represents the model behind the search form about `common\models\CoupanCode`.
 */
class CoupanCodeSearch extends CoupanCode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'amount','merchant_id'], 'integer'],
            [['promo_code', 'status', 'amount_type', 'applied_on', 'expire_date', 'applied_merchant'], 'safe'],
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
        $query = CoupanCode::find();

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
            'amount' => $this->amount,
            'expire_date' => $this->expire_date,
        ]);

        $query->andFilterWhere(['like', 'promo_code', $this->promo_code])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'amount_type', $this->amount_type])
            ->andFilterWhere(['like', 'applied_on', $this->applied_on])
            ->andFilterWhere(['like', 'applied_merchant', $this->applied_merchant]);

        return $dataProvider;
    }
}
