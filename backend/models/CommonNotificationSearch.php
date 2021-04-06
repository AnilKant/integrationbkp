<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CommonNotification;

/**
 * CommonNotificationSearch represents the model behind the search form about `backend\models\CommonNotification`.
 */
class CommonNotificationSearch extends CommonNotification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort_order'], 'integer'],
            [['html_content', 'from_date', 'to_date', 'enable', 'marketplace'], 'safe'],
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
        $query = CommonNotification::find();

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
            'sort_order' => $this->sort_order,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);

        $query->andFilterWhere(['like', 'html_content', $this->html_content])
            ->andFilterWhere(['like', 'enable', $this->enable])
            ->andFilterWhere(['like', 'marketplace', $this->marketplace]);

        return $dataProvider;
    }
}
