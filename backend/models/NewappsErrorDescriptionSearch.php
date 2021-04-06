<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\NewappsErrorDescription;

/**
 * WalmartErrorDescriptionSearch represents the model behind the search form about `backend\models\WalmartErrorDescription`.
 */
class NewappsErrorDescriptionSearch extends NewappsErrorDescription
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'error_type', 'is_enable'], 'integer'],
            [['marketplace','error_code', 'error_description', 'error_solution'], 'safe'],
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
        $query = NewappsErrorDescription::find();

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
            'error_type' => $this->error_type,
            'is_enable' => $this->is_enable,
        ]);

        $query->andFilterWhere(['like', 'error_code', $this->error_code])
            ->andFilterWhere(['like', 'marketplace', $this->marketplace])
            ->andFilterWhere(['like', 'error_description', $this->error_description])
            ->andFilterWhere(['like', 'error_solution', $this->error_solution]);

        return $dataProvider;
    }
}
