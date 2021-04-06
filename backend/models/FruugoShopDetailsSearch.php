<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\FruugoShopDetails;
use yii\db\Expression;

/**
 * FruugoShopDetailsSearch represents the model behind the search form about `backend\models\FruugoShopDetails`.
 */
class FruugoShopDetailsSearch extends FruugoShopDetails
{
    /**
     * @inheritdoc
     */
    public $install_date2;
    public $expire_date2;
    public $purchase_status;
    public $uninstall_date2;
    public function rules()
    {
        return 
        [
            [['id', 'merchant_id'], 'integer'],
            [['shop_url', 'shop_name', 'email', 'token', 'install_status', 'install_date', 'verified_email','uninstall_date', 'uninstall_status', 'expire_date', 'purchase_status', 'created_at', 'updated_at','config','allowed_sku'], 'safe'],
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
        $query = FruugoShopDetails::find();

        // add conditions that should always apply here
        $query->select(['`fruugo_shop_details`.*,`merchant`.`verified_email`, `merchant`.`verified_mobile` as `mobile`','fruugo_configuration.seller_email AS config']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->innerJoin('merchant','`merchant`.`merchant_id` = `fruugo_shop_details`.`merchant_id`');

        $query->leftJoin('fruugo_configuration', '`fruugo_configuration`.`merchant_id` = `fruugo_shop_details`.`merchant_id` AND `fruugo_configuration`.`seller_email` IS NOT NULL');

         /*if($this->purchase_status!= '' && $this->purchase_status=='Live'){

            $query->andFilterWhere([
                'purchase_status' => $this->purchase_status,
            ]);
        }elseif($this->purchase_status!= '' && $this->purchase_status=='Ammendment'){
           
            $query->andFilterWhere([
                'purchase_status' => $this->purchase_status,
            ]);
        }*/

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            '`fruugo_shop_details`.merchant_id' => $this->merchant_id,
            'install_date' => $this->install_date,
            'uninstall_date' => $this->uninstall_date,
            'expire_date' => $this->expire_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if(!empty($this->config)) {
            if($this->config == 'yes') {
                $query->andFilterWhere(['IS', 'fruugo_configuration.seller_email', new Expression('NOT NULL')]);
            } else {
                $query->andFilterWhere(['IS', 'fruugo_configuration.seller_email', new Expression('NULL')]);
            }
        }

        $query->andFilterWhere(['like', 'shop_url', $this->shop_url])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'fruugo_shop_details.email', $this->email])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'install_status', $this->install_status])
            ->andFilterWhere(['like', 'uninstall_status', $this->uninstall_status])
            ->andFilterWhere(['like', 'purchase_status', $this->purchase_status]);

         // var_dump($query->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql);die('asdfsss');
        return $dataProvider;

        //'SELECT fruugo_product_variants.merchant_id, COUNT(IF( fruugo_product_variants.status = 'INSTOCK', fruugo_product_variants.status, NULL)) AS instock, COUNT(IF( fruugo_product_variants.status = 'OUTOFSTOCK', fruugo_product_variants.status, NULL)) AS outstock, COUNT(IF( fruugo_product_variants.status = 'Not Uploaded', fruugo_product_variants.status, NULL)) AS notuploaded FROM `fruugo_product_variants` GROUP BY merchant_id'
    }
}
// 