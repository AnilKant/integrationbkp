<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AppsFaq;

/**
 * AppsFaqSearch represents the model behind the search form about `common\models\AppsFaq`.
 */
class AppsFaqSearch extends AppsFaq
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['marketplace', 'query', 'description', 'is_enabled','faq_type','faq_category'], 'safe'],
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
        $query = AppsFaq::find();

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

        $query->andFilterWhere(['like', 'marketplace', $this->marketplace])
            ->andFilterWhere(['like', 'query', $this->query])
            ->andFilterWhere(['like', 'is_enabled', $this->is_enabled])
            ->andFilterWhere(['like', 'faq_type', $this->faq_type])
            ->andFilterWhere(['like', 'faq_category', $this->faq_category])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
