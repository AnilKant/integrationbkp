<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\JetShopDetails;
use backend\components\Data;
use yii\db\Expression;

/**
 * JetShopDetailsSearch represents the model behind the search form about `backend\models\JetShopDetails`.
 */
class JetShopDetailsSearch extends JetShopDetails
{
    /**
     * @inheritdoc
     */
    public $review_to, $review_from, $live_to, $live_from, $order_to, $order_from;

    public $customWhere = '';
    public $user_status;
    public $config;
    public $mobile;

    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
            [['shop_url', 'shop_name', 'email', 'country_code', 'currency', 'install_status', 'installed_on', 'expired_on', 'purchased_on', 'purchase_status','review_to','review_from','live_to','live_from','order_to','order_from','seller_username','seller_password','last_login','ip_address','config','mobile'], 'safe'],
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

    public function setCustomWhere($where){
        $this->customWhere = $where;
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
        if($this->customWhere!=''){
            $query = JetShopDetails::find()->where($this->customWhere);
        }else{
            $query = JetShopDetails::find();
        }

        $query->select(['`jet_shop_details`.*, `user`.`status` as `user_status`,`user`.`mobile` as `mobile`','jet_configuration.api_user AS config']);

        // add conditions that should always apply here

        $pageSize = isset($params['per-page']) ? intval($params['per-page']) : 50;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>  ['pageSize' => $pageSize],
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->innerJoin('user', 'user.id = jet_shop_details.merchant_id');
        $query->leftJoin('jet_configuration', '`jet_configuration`.`merchant_id` = `jet_shop_details`.`merchant_id` AND `jet_configuration`.`api_user` IS NOT NULL');

        if(!empty($this->config)) {
            if($this->config == 'yes') {
                $query->andFilterWhere(['IS', 'jet_configuration.api_user', new Expression('NOT NULL')]);
            } else {
                $query->andFilterWhere(['IS', 'jet_configuration.api_user', new Expression('NULL')]);
            }
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'jet_shop_details.merchant_id' => $this->merchant_id,
            'installed_on' => $this->installed_on,
            'expired_on' => $this->expired_on,
            'purchased_on' => $this->purchased_on,
        ]);

        $query->andFilterWhere(['like', 'shop_url', $this->shop_url])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'jet_shop_details.email', $this->email])
            ->andFilterWhere(['like', 'country_code', $this->country_code])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'seller_username', $this->seller_username])
            ->andFilterWhere(['like', 'seller_password', $this->seller_password])
            ->andFilterWhere(['like', 'install_status', $this->install_status])
            ->andFilterWhere(['like', 'purchase_status', $this->purchase_status])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'last_login', $this->last_login]);

        if(!is_null($this->user_status)) {
            $query->andFilterWhere(['=', '`user`.`status`', $this->user_status]);
        }
        
        // var_dump($query->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();
        //var_dump($dataProvider->getModels());die('hh');
        return $dataProvider;
    }

}