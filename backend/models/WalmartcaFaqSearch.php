<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class WalmartcaFaqSearch extends WalmartcaFaq
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['query', 'description'], 'safe'],
            /*[['marketplace'], 'exist','allowArray' => true,'when' => function ($model, $attribute) { return is_array($model->$attribute);}],*/
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
        $query = WalmartcaFaq::find();

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

        $query->andFilterWhere(['like', 'query', $this->query])
            ->andFilterWhere(['like', 'description', $this->description]);
            // ->andFilterWhere(['like', 'marketplace', $this->marketplace]);

            // print_r($dataProvider);die('die...dataProvider');
        return $dataProvider;
    }
}