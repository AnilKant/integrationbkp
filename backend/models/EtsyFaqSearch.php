<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 8/3/18
 * Time: 5:24 PM
 */

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class EtsyFaqSearch extends EtsyFaq
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['query', 'description'], 'safe'],
            [['marketplace'], 'exist','allowArray' => true,'when' => function ($model, $attribute) { return is_array($model->$attribute);}],
           // [['marketplace'], 'exist','allowArray' => true]

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
        $query = EtsyFaq::find();

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
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'marketplace', $this->marketplace]);

        return $dataProvider;
    }
}