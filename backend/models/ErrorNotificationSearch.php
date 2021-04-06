<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ErrorNotification;
use frontend\modules\walmart\components\Product\ProductHelper;
use frontend\modules\walmart\components\TableConstant;
use yii\db\Query;

/**
 * ErrorNotificationSearch represents the model behind the search form about `backend\models\ErrorNotification`.
 */
class ErrorNotificationSearch extends ErrorNotification
{
    /**
     * @inheritdoc
     */
   
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'error_type'], 'integer'],
            [['identifier', 'identifier_type', 'marketplace', 'reason', 'data', 'created_at', 'updated_at'], 'safe'],
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
        // print_r($params);die("zdfg");
        // $query = ErrorNotification::find();
        // $merchant_id = Yii::$app->user->identity->id;
        $str = "`wsd`.`status`=1";
        $query = (new Query())
            ->select("COUNT(*) as `error_count`,MAX(`en`.`created_at`) as `created_at`,`en`.`merchant_id`, `en`.`error_type`,`wsd`.`shop_url`, `wsd`.`email`,")
            ->from(TableConstant::WALMART_ERROR_NOTIFICATION . ' en')
            ->join('LEFT JOIN', TableConstant::WALMART_SHOP_DETAILS . ' wsd', '`wsd`.`merchant_id`=`en`.`merchant_id`')
            ->where($str)
            ->groupBy('en.`merchant_id`');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => isset($params['per-page']) ? intval($params['per-page']) : 25],
            'sort' => ['attributes' => ['error_count']]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            
            'en.merchant_id' => $this->merchant_id,
            'en.error_type' => $this->error_type,
            'en.created_at' => $this->created_at,
            'en.updated_at' => $this->updated_at,
        ]);

        if (isset($_GET['inventory'])) {
             $query->andWhere('en.`error_type` ='.ProductHelper::INVENTORY_UPDATE_ERROR);
        }elseif(isset($_GET['product_delete'])){
            $query->andWhere('en.`error_type` ='.ProductHelper::PRODUCT_DELETE);
        }
        elseif(isset($_GET['product_update'])){
            $query->andWhere('en.`error_type` ='.ProductHelper::PRODUCT_UPDATE);
        }
        elseif(isset($_GET['product_create'])){
            $query->andWhere('en.`error_type` ='.ProductHelper::PRODUCT_CREATE);
        }
        elseif(isset($_GET['price_update'])){
            $query->andWhere('en.`error_type` ='.ProductHelper::PRICE_UPDATE_ERROR);
        }
        elseif(isset($_GET['sku_update'])){
            $query->andWhere('en.`error_type` ='.ProductHelper::SKU_UPDATE_ERROR);
        }
        elseif(isset($_GET['order_shipment'])){
            $query->andWhere('en.`error_type` ='.ProductHelper::ORDER_SHIPMENT);
        }
        elseif(isset($_GET['marketplace_delete_error'])){
            $query->andWhere('en.`error_type` ='.ProductHelper::PRODUCT_DELETE_ERROR);
        }

        $query->andFilterWhere(['like', 'identifier', $this->identifier])
            ->andFilterWhere(['like', 'identifier_type', $this->identifier_type])
            ->andFilterWhere(['like', 'marketplace', $this->marketplace])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'data', $this->data]);

        // $query->orderBy('error_notification.`created_at` DESC');
        // var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();

        return $dataProvider;
    }
}
