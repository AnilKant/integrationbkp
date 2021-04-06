<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\EtsyShopDetails;

/**
 * EtsyShopDetailsSearch represents the model behind the search form about `backend\models\EtsyShopDetails`.
 */
class EtsyShopDetailsSearch extends EtsyShopDetails
{
    /**
     * @inheritdoc
     */

    public $install_date2;
    public $expire_date2;
    public $uninstall_date2;
    public $install_date;
    public $expire_date;
    public $merhcant;
    public $shopurl;
    public $email;
    public $shopname;
    public $status;
    public $config_path;
    public $owner_name;

    public function rules()
    {
        return [
            [['id', 'merchant_id', 'product_limit','sku_upload_limit','is_digital_allowed'], 'integer'],
            [['token', 'install_status', 'install_date','install_date2', 'uninstall_date', 'purchase_status', 'last_login_ip', 'last_login_time', 'expire_date','expire_date2', 'created_at', 'updated_at','shopurl','shopname','email','config_path','owner_name','store_status','limit_renew_till','do_not_contact'], 'safe'],
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
        $query = EtsyShopDetails::find();
        $query->leftJoin(['etsy_installation'], 'etsy_shop_details.merchant_id = etsy_installation.merchant_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'sort'=> ['defaultOrder' => ['merchant_id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['merchant']);

        if($this->config_path != '' && $this->config_path=='yes'){
            $query->andWhere("`etsy_installation`.`step` >= 1");
        }elseif($this->config_path != '' && $this->config_path=='no'){
            $query->andWhere("`etsy_installation`.`step` IS NULL");
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'merchant.merchant_id' => $this->merchant_id,
            'uninstall_date' => $this->uninstall_date,
            'last_login_time' => $this->last_login_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product_limit' => $this->product_limit,
            'store_status' => $this->store_status,
            'do_not_contact'=>$this->do_not_contact,
            'is_digital_allowed'=>$this->is_digital_allowed
        ]);

        $query->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'install_status', $this->install_status])
            ->andFilterWhere(['like', 'purchase_status', $this->purchase_status])
            ->andFilterWhere(['like', 'last_login_ip', $this->last_login_ip])
             ->andFilterWhere(['like', 'merchant.shopurl', $this->shopurl])
             ->andFilterWhere(['like', 'merchant.shopname', $this->shopname])
            ->andFilterWhere(['like', 'merchant.owner_name', $this->owner_name])
             ->andFilterWhere(['>=', 'install_date' , $this->install_date])
             ->andFilterWhere(['<=', 'install_date' , $this->install_date2])
             ->andFilterWhere(['>=', 'expire_date' , $this->expire_date])
             ->andFilterWhere(['<=', 'expire_date' , $this->expire_date2])
            ->andFilterWhere(['>=', 'uninstall_date' , $this->uninstall_date])
            ->andFilterWhere(['<=', 'uninstall_date' , $this->uninstall_date2])
             ->andFilterWhere(['like', 'merchant.email' , $this->email]);
//        var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die("ll");
        return $dataProvider;
    }
}
