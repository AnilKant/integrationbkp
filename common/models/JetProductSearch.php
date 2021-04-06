<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JetProduct;

/**
 * JetProductSearch represents the model behind the search form about `app\models\JetProduct`.
 */
class JetProductSearch extends JetProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qty'], 'integer'],
            [['merchant_id', 'title', 'sku','product_type','type', 'description', 'image', 'attr_ids', 'jet_attributes', 'upc', 'ASIN','vendor','fulfillment_node','status'], 'safe'],
            [['price'], 'number'],
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
       // $query = JetProduct::find();
    	$merchant_id=\Yii::$app->user->identity->id;
    	$query = JetProduct::find()->where(['merchant_id' => $merchant_id]);

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
            'qty' => $this->qty,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'merchant_id', $this->merchant_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'product_type', $this->product_type])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'vendor', $this->vendor])
            ->andFilterWhere(['like', 'attr_ids', $this->attr_ids])
            ->andFilterWhere(['like', 'jet_attributes', $this->jet_attributes])
            ->andFilterWhere(['like', 'upc', $this->upc])
            //->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'ASIN', $this->ASIN])
            ->andFilterWhere(['like', 'type', $this->type])
            //->andFilterWhere(['like', 'manufacturer_part_number', $this->manufacturer_part_number])
            ->andFilterWhere(['like', 'fulfillment_node', $this->fulfillment_node])
        	->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
