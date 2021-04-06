<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AppliedCoupanCode;

/**
 * AppliedCoupanCodeSearch represents the model behind the search form about `common\models\AppliedCoupanCode`.
 */
class AppliedCoupanCodeSearch extends AppliedCoupanCode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'coupan_code_id'], 'integer'],
            [['used_on', 'coupan_code', 'activated_date'], 'safe'],
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
        $query = AppliedCoupanCode::find();

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
            'activated_date' => $this->activated_date,
            'coupan_code_id' => $this->coupan_code_id,
        ]);

        $query->andFilterWhere(['like', 'used_on', $this->used_on])
            ->andFilterWhere(['like', 'coupan_code', $this->coupan_code]);

        return $dataProvider;
    }
}
