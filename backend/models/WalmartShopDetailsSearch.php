<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WalmartShopDetails;
use backend\models\WalmartExtensionDetails;
use backend\components\Data;
use yii\db\Expression;

/**
 * WalmartShopDetailsSearch represents the model behind the search form about `backend\models\WalmartShopDetails`.
 */
class WalmartShopDetailsSearch extends WalmartShopDetails
{
    /**
     * @inheritdoc
     */
    public $install_date;
    public $install_date2;
    public $expire_date;
    public $expire_date2;
    public $status1;
    public $walmartShopDetails;
    public $merchant_id;
    public $merchant_id2;
    public $uninstall_date;
    public $uninstall_date2;
    public $user_status;
    public $mobile;
    
    public function rules()
    {
        return [
            [['id', 'merchant_id','status'], 'integer'],
            [['shop_url','shop_name', 'email', 'token', 'currency','last_login','install_country','ip_address','walmartShopDetails','install_date','status1','install_date2','expire_date','expire_date2','uninstall_date','uninstall_date2','seller_username','seller_password','config','user_status','mobile'], 'safe'],
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
        /*$query = WalmartShopDetails::find()->select(['`walmart_shop_details`.*, `user`.`status` as `user_status`,`user`.`mobile` as `mobile`','walmart_configuration.consumer_id AS config']);*/

         $query = WalmartShopDetails::find()->select(['`walmart_shop_details`.*, `user`.`status` as `user_status`,`user`.`mobile` as `mobile`','IF(`walmart_config`.`data` IS NOT NULL,"YES","NO") as `config`']);

        /*$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);*/
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['attributes'=>['install_date','expire_date','merchant_id','shop_url','shop_name','email','user_status'], 'defaultOrder' => ['install_date'=>SORT_DESC]]
        ]);
         /*$dataProvider->sort->attributes['walmartExtensionDetail'] = [
        'asc' => ['walmart_extension_detail.status' => SORT_ASC],
        'desc' => ['walmart_extension_detail.status' => SORT_DESC],
        'asc' => ['walmart_extension_detail.install_date' => SORT_ASC],
        'desc' => ['walmart_extension_detail.install_date' => SORT_DESC],
        'asc' => ['walmart_extension_detail.expire_date' => SORT_ASC],
        'desc' => ['walmart_extension_detail.expire_date' => SORT_DESC],
    ];*/


        $this->load($params);

        if (!$this->validate()) {
             $dataProvider = new ActiveDataProvider;
            return $dataProvider;
        }
         $query->joinWith(['walmartExtensionDetail']);
        // $query->leftJoin('walmart_configuration', '`walmart_configuration`.`merchant_id` = `walmart_shop_details`.`merchant_id` AND `walmart_configuration`.`consumer_id` IS NOT NULL');
          $query->leftJoin('(SELECT * FROM `walmart_config` WHERE `data` = "client_id") as walmart_config','`walmart_config`.`merchant_id` = `walmart_shop_details`.`merchant_id`');

        $query->innerJoin('user', 'user.id = walmart_shop_details.merchant_id');

        if(!empty($this->config)) 
        {
            if($this->config == 'yes') {
                // $query->andFilterWhere(['IS', 'walmart_configuration.consumer_id', new Expression('NOT NULL')]);
              /*  $query->orFilterWhere([
                    '`walmart_config`.`data`' => 'client_id',
                    ]);*/
                    $query->orFilterWhere(['IS', 'walmart_config.data', new Expression('NOT NULL')]);
            } else {
                $query->andFilterWhere(['IS', 'walmart_config.data', new Expression('NULL')]);
                // $query->andFilterWhere(['IS', 'walmart_configuration.consumer_id', new Expression('NULL')]);
            }
        }

        $query->andFilterWhere([
            'walmart_shop_details.merchant_id' => $this->merchant_id,
            'walmart_shop_details.status'=>$this->status,
            'walmart_extension_detail.status'=> $this->status1,
        ]);



        if(!is_null($this->user_status)) {
            $query->andFilterWhere(['=', '`user`.`status`', $this->user_status]);
        }

        if(!is_null($this->ip_address)) {
            $query->andFilterWhere(['=', '`ip_address`', $this->ip_address]);
        }

        $query->andFilterWhere(['like', 'shop_url', $this->shop_url])
/*            ->andFilterWhere(['like', 'merchant_id', $this->merchant_id2])*/
            ->andFilterWhere(['like', 'walmart_shop_details.shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'seller_username', $this->seller_username])
            ->andFilterWhere(['like', 'seller_password', $this->seller_password])
            ->andFilterWhere(['>=','walmart_extension_detail.install_date',$this->install_date])
            ->andFilterWhere(['<=','walmart_extension_detail.install_date',$this->install_date2])
            ->andFilterWhere(['>=','walmart_extension_detail.expire_date',$this->expire_date])
            ->andFilterWhere(['<=','walmart_extension_detail.expire_date',$this->expire_date2])
            ->andFilterWhere(['>=','walmart_extension_detail.uninstall_date',$this->uninstall_date])
            ->andFilterWhere(['<=','walmart_extension_detail.uninstall_date',$this->uninstall_date2]);
       // var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die;
//        print_r($dataProvider->getModels());
//        die;

        return $dataProvider;
    }

    public function filterConfigSet()
    {
        $query = "SELECT `merchant_id` FROM  `walmart_configuration`";
        $result = Data::sqlRecords($query, 'column');
        return ($result);
    }
}
