<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OnbuyShopDetails;
use backend\components\onbuy\TableConstant;

/**
 * OnbuyShopDetailsSearch represents the model behind the search form about `backend\models\OnbuyShopDetails`.
 */
class OnbuyShopDetailsSearch extends OnbuyShopDetails
{
    /**
     * @inheritdoc
     */
    public $shopurl;
    public $email;
    public $status;
    public $owner_name;

    // public $lname;
    // public $seller_username;
    // public $seller_password;

    public function rules()
    {
        return [
            [['id', 'merchant_id', 'install_status', 'purchase_status'], 'integer'],
            [['shopurl','email','token', 'install_date', 'verified_mobile','uninstall_date','expire_date', 'ip_address', 'last_login', 'updated_at', 'created_at','status'], 'safe'],
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
//var_dump($this->status);die;
        $query = OnbuyShopDetails::find()->select([TableConstant::SHOP_DETAILS.'.*','merchant.shopurl','merchant.email','merchant.seller_username','merchant.seller_password','merchant.verified_mobile',TableConstant::INSTALLATION.'.status','merchant.owner_name']);

        // add conditions that should always apply here
        $query->innerJoin('merchant', '`merchant`.`merchant_id` = `'.TableConstant::SHOP_DETAILS.'`.`merchant_id`');
        $query->leftJoin(TableConstant::INSTALLATION, '`'.TableConstant::INSTALLATION.'`.`merchant_id` = `'.TableConstant::SHOP_DETAILS.'`.`merchant_id`');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>[
                'attributes'=>[
                    'merchant_id','install_status','install_date','uninstall_date','purchase_status','expire_date', 'ip_address', 'last_login', 'shopurl', 'email', 'config','fname','lname'
                ],
                'defaultOrder' => [
                    'install_date'=>SORT_DESC
                ]
            ]
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

       if($this->status==1){

            $query->andWhere(TableConstant::INSTALLATION.".`status` = 1");
        }elseif($this->status==0 && !is_null($this->status) && $this->status!=''){
            $query->andWhere(TableConstant::INSTALLATION.".`status` IS NULL");
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
           // 'merchant_id' => $this->merchant_id,
            'install_status' => $this->install_status,
            'install_date' => $this->install_date,
            'uninstall_date' => $this->uninstall_date,
            'purchase_status' => $this->purchase_status,
            'expire_date' => $this->expire_date,
            'last_login' => $this->last_login,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'merchant.shopurl', $this->shopurl])
              ->andFilterWhere(['like', 'merchant.email', $this->email])
              ->andFilterWhere(['like', 'merchant.verified_mobile', $this->verified_mobile])
              ->andFilterWhere(['=', 'merchant.merchant_id', $this->merchant_id]);
              //->andFilterWhere(['=', TableConstant::INSTALLATION.'.status', $this->status]);

        $query->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address]);

        // echo "<pre>";print_r($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();
        return $dataProvider;
    }
}
