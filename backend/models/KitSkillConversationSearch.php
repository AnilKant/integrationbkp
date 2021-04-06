<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\KitSkillConversation;

/**
 * KitSkillConversationSearch represents the model behind the search form about `backend\models\KitSkillConversation`.
 */
class KitSkillConversationSearch extends KitSkillConversation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['shop_name', 'conversation_uid', 'type', 'response', 'updated_at', 'created_at', 'app_name', 'skill_name'], 'safe'],
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
        $query = KitSkillConversation::find();
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
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'conversation_uid', $this->conversation_uid])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'response', $this->response])
            ->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'skill_name', $this->skill_name]);

        return $dataProvider;
    }
}
