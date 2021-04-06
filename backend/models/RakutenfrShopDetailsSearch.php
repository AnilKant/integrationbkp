<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RakutenfrShopDetails;

/**
 * RakutenfrShopDetailsSearch represents the model behind the search form about `backend\models\RakutenfrShopDetails`.
 */
class RakutenfrShopDetailsSearch extends RakutenfrShopDetails
{
    /**
     * @inheritdoc
     */
    public $shopurl;
    public $email;
    public $status;
    public $fname;
    public $lname;
    public $seller_username;
    public $seller_password;
    public $currency;

    public function rules()
    {
        return [
            [['id', 'merchant_id', 'install_status', 'purchase_status'], 'integer'],
            [['shopurl','email','token', 'install_date', 'verified_mobile', 'verified_email','owner_name','uninstall_dates','expire_date', 'ip_address', 'last_login', 'updated_at', 'created_at','status'], 'safe'],
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
        $query = RakutenfrShopDetails::find()->select(['rakuten_fr_shop_details.*','merchant.currency','merchant.shopurl','merchant.email','merchant.verified_email','merchant.seller_username','merchant.seller_password','merchant.verified_mobile', 'merchant.owner_name','rakuten_fr_installation.status']);

        // add conditions that should always apply here
        $query->innerJoin('merchant', '`merchant`.`merchant_id` = `rakuten_fr_shop_details`.`merchant_id`');
        $query->leftJoin('rakuten_fr_installation', '`rakuten_fr_installation`.`merchant_id` = `rakuten_fr_shop_details`.`merchant_id`');
        // $query->leftJoin('rakutenus_registration', '`rakutenfr_registration`.`merchant_id` = `rakutenus_shop_details`.`merchant_id`');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>[
                'attributes'=>[
                    'merchant_id','install_status','install_date','uninstall_dates','purchase_status','expire_date', 'ip_address', 'last_login', 'shopurl', 'email', 'config','fname','lname','currency','owner_name'
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
           // 'merchant_id' => $this->merchant_id,
            'install_status' => $this->install_status,
            'install_date' => $this->install_date,
            'uninstall_dates' => $this->uninstall_dates,
            'purchase_status' => $this->purchase_status,
            'expire_date' => $this->expire_date,
            'last_login' => $this->last_login,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'merchant.shopurl', $this->shopurl])
              ->andFilterWhere(['like', 'merchant.email', $this->email])
              ->andFilterWhere(['like', 'merchant.verified_mobile', $this->verified_mobile])
              ->andFilterWhere(['like', 'merchant.owner_name', $this->owner_name])
              ->andFilterWhere(['=', 'merchant.merchant_id', $this->merchant_id])
              ->andFilterWhere(['=', 'rakuten_fr_installation.status', $this->status]);

        $query->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address]);

        // echo "<pre>";print_r($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();
        return $dataProvider;
    }
}
