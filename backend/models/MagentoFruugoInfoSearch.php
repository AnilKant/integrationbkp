<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MagentoFruugoInfo;

/**
 * MagentoFruugoInfoSearch represents the model behind the search form about `backend\models\MagentoFruugoInfo`.
 */
class MagentoFruugoInfoSearch extends MagentoFruugoInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'live_sku', 'uploaded_sku'], 'integer'],
            [['domain', 'email', 'total_revenue', 'install_on', 'framework','fruugo_username','fruugo_password'], 'safe'],
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
        $query = MagentoFruugoInfo::find();

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
            'live_sku' => $this->live_sku,
            'uploaded_sku' => $this->uploaded_sku,
            'last_synced' => $this->last_synced,
            'install_on' => $this->install_on,
        ]);

        $query->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'total_revenue', $this->total_revenue])
            ->andFilterWhere(['like', 'framework', $this->framework]);

        return $dataProvider;
    }
}
