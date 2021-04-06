<?php

namespace backend\models;

use backend\models\WalmartConfiguration;
use backend\models\WalmartClientDetails;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * JetTestApiSearch represents the model behind the search form about `backend\models\JetTestApi`.
 */

class WalmartClientDetailsSearch extends WalmartClientDetails
{
    /**
     * @inheritdoc
     */
    public $consumer_id;
    public $secret_key;

    public function rules()
    {
        return [
            [['fname', 'lname', 'merchant_id', 'mobile', 'email', 'secret_key','consumer_id'], 'safe'],
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
        $query = WalmartClientDetails::find();



        $this->load($params);
        /*$dataProvider->sort->attributes['configuration'] = [
        'asc' => ['configuration.name' => SORT_ASC],
        'desc' => ['configuration.name' => SORT_DESC],
        'asc' => ['configuration.shipping_source' => SORT_ASC],
        'desc' => ['configuration.shipping_source' => SORT_DESC],

        ];*/
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['configuration']);
        /**to make an alias of joined table */
        /*$query->joinWith([
            'configuration' => function ($query) {
                // @var $query \yii\db\ActiveQuery

                $query->from(WalmartConfiguration::tableName() . ' configuration');
                // or $query->from(['configuration' => WalmartConfiguration::tableName()]);
            },
        ]);*/
        /**to make an alias of joined table ends */
        $query->andFilterWhere([
            'id' => $this->id,
            //'merchant_id' => $this->merchant_id,
        ]);

        $query->andFilterWhere(['like', 'fname', $this->fname])

            ->andFilterWhere(['like', 'lname', $this->lname])
            ->andFilterWhere(['walmart_registration.merchant_id'=>$this->merchant_id])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'configuration.consumer_id', $this->consumer_id])
            ->andFilterWhere(['like', 'configuration.secret_key', $this->secret_key]);
        //var_dump($query->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
