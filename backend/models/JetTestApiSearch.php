<?php

namespace backend\models;

use common\models\JetRegistration;
use backend\models\JetTestApi;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * JetTestApiSearch represents the model behind the search form about `backend\models\JetTestApi`.
 */

class JetTestApiSearch extends JetTestApi
{
    /**
     * @inheritdoc
     */
	public $name;
	public $shipping_source;
	public $mobile;
    public $email;
    public $registration;

    public function rules()
    {
        return [
            [['id', 'merchant_id', 'mobile'], 'integer'],
            [['name', 'shipping_source', 'mobile','email','user','secret','fulfillment_node','merchant'], 'safe'],
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
        $query = JetTestApi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $dataProvider->sort->attributes['registration'] = [
        'asc' => ['registration.name' => SORT_ASC],
        'desc' => ['registration.name' => SORT_DESC],
        'asc' => ['registration.shipping_source' => SORT_ASC],
        'desc' => ['registration.shipping_source' => SORT_DESC],
        'asc' => ['registration.mobile' => SORT_ASC],
        'desc' => ['registration.mobile' => SORT_DESC],
        'asc' => ['registration.email' => SORT_ASC],
        'desc' => ['registration.email' => SORT_DESC],
        ];
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['registration']);
        $query->andFilterWhere([
            'id' => $this->id,
            'jet_test_api.merchant_id' => $this->merchant_id,
        ]);

        $query->andFilterWhere(['like', 'merchant', $this->merchant])
                ->andFilterWhere(['like', 'user', $this->user])
                ->andFilterWhere(['like', 'secret', $this->secret])
                ->andFilterWhere(['like', 'merchant', $this->merchant])
                ->andFilterWhere(['like', 'fulfillment_node', $this->fulfillment_node])
                ->andFilterWhere(['like', 'registration.name', $this->name])
                ->andFilterWhere(['like', 'registration.shipping_source', $this->shipping_source])
                ->andFilterWhere(['like', 'registration.mobile', $this->mobile])
            	->andFilterWhere(['like', 'registration.email', $this->email]);

        return $dataProvider;
    }
}
