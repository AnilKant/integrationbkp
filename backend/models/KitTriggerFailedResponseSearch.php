<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\KitTriggerFailedResponse;

/**
 * KitTriggerFailedResponseSearch represents the model behind the search form about `backend\models\KitTriggerFailedResponse`.
 */
class KitTriggerFailedResponseSearch extends KitTriggerFailedResponse
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['shop_name', 'skill_name', 'response', 'updated_at', 'created_at', 'app_name'], 'safe'],
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
        $query = KitTriggerFailedResponse::find();
        $db = 'admin';
        if(isset($params['app'])){
            if($params['app'] == 'new'){
                $db = 'admin';
            }elseif($params['app'] == 'old'){
                $db = 'db';
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'db' => $db
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'skill_name', $this->skill_name])
            ->andFilterWhere(['like', 'response', $this->response])
            ->andFilterWhere(['like', 'app_name', $this->app_name]);

        return $dataProvider;
    }
}
