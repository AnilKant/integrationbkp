<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SearsExtensionDetail;
use yii\db\Expression;

/**
 * SearsExtensionDetailSearch represents the model behind the search form about `backend\models\SearsExtensionDetail`.
 */
class SearsExtensionDetailSearch extends SearsExtensionDetail
{
    public $customWhere = '';
    // public $attribute ;
    public function setCustomWhere($where){
        $this->customWhere = $where;
    }
    public $review_to, $review_from, $live_to, $live_from, $order_to, $order_from,$shop_url,$email,$shop_name,$last_login_IP,$last_login_time;

    public $user_status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
            [['install_date', 'date', 'expire_date','shop_url', 'shop_name', 'email' ,'status', 'app_status', 'uninstall_date','panel_password','panel_username','user_status','mobile','config'], 'safe'],

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
        if($this->customWhere!=''){
            $query = SearsExtensionDetail::find()->where($this->customWhere);
        }else{
            $query = SearsExtensionDetail::find();
        }
        
        $query->select(['`sears_extension_detail`.*, `user`.`status` as `user_status`, `user`.`mobile` as `mobile`','sears_configuration.seller_id AS config']);

        $pageSize = isset($params['per-page']) ? intval($params['per-page']) : 50;
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' =>  ['pageSize' => $pageSize],
                'sort'=> ['defaultOrder' => ['merchant_id'=>SORT_ASC]],
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['sears_shop_details']);

        $query->innerJoin('user', 'user.id = sears_extension_detail.merchant_id');
        $query->leftJoin('sears_configuration', '`sears_configuration`.`merchant_id` = `sears_extension_detail`.`merchant_id` AND `sears_configuration`.`seller_id` IS NOT NULL');

        if(!empty($this->config)) {
            if($this->config == 'yes') {
                $query->andFilterWhere(['IS', 'sears_configuration.seller_id', new Expression('NOT NULL')]);
            } else {
                $query->andFilterWhere(['IS', 'sears_configuration.seller_id', new Expression('NULL')]);
            }
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'sears_shop_details.merchant_id' => $this->merchant_id,
            'install_date' => $this->install_date,
            'date' => $this->date,
            'expire_date' => $this->expire_date,
            'uninstall_date' => $this->uninstall_date,
            'app_status'=> $this->app_status,
            'sears_shop_details.last_login_IP' => $this->last_login_IP,
            'sears_shop_details.last_login_time' => $this->last_login_time,
        ]);
        
        if(!is_null($this->user_status)) {
            $query->andFilterWhere(['=', '`user`.`status`', $this->user_status]);
        }

        $query->andFilterWhere(['like', 'sears_extension_detail.status', $this->status])
            ->andFilterWhere(['like', 'sears_shop_details.shop_url', $this->shop_url])
            ->andFilterWhere(['like', 'sears_shop_details.shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'panel_username', $this->panel_username])
            ->andFilterWhere(['like', 'panel_password', $this->panel_password])
            ->andFilterWhere(['like', 'sears_shop_details.last_login_IP', $this->last_login_IP])
            ->andFilterWhere(['like', 'sears_shop_details.last_login_time', $this->last_login_time])
            ->andFilterWhere(['like', 'sears_shop_details.email', $this->email]);
	    //var_dump($query->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();
	    return $dataProvider;
    }
}
