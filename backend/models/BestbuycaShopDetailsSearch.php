<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BestbuycaShopDetails;
use common\models\Merchant;

/**
 * BestbuycaShopDetailsSearch represents the model behind the search form about `backend\models\BestbuycaShopDetails`.
 */
class BestbuycaShopDetailsSearch extends BestbuycaShopDetails
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
    public $owner_name;
    public $status;
    public $config_path;
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'maximum_prod_limit'], 'integer'],
            [['token', 'install_status', 'install_date','install_date2', 'uninstall_date', 'purchase_status', 'last_login_ip', 'last_login_time', 'expire_date','expire_date2', 'created_at', 'updated_at', 'seller_username', 'seller_password','shopurl','shopname','email','config_path','owner_name',
            'import_renew_till'], 'safe'],
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
        $query = BestbuycaShopDetails::find();
        $query->leftJoin(['bestbuyca_installation'], 'bestbuyca_shop_details.merchant_id = bestbuyca_installation.merchant_id');

        // add conditions that should always apply here

        $pageSize = isset($params['per-page']) ? intval($params['per-page']) : 50;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>  ['pageSize' => $pageSize],
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $dataProvider = new ActiveDataProvider;
            return $dataProvider;
        }

        $query->joinWith(['merchant']);

        if($this->config_path != '' && $this->config_path=='yes'){

            $query->andWhere("bestbuyca_installation.step > 2");
        }elseif($this->config_path != '' && $this->config_path=='no'){

           // $query->andWhere("bestbuyca_installation.step < 2");
            $query->andWhere("bestbuyca_installation.step < 2 OR bestbuyca_installation.step IS NULL");

        }
        // grid filtering conditions
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'merchant.merchant_id' => $this->merchant_id,
           // 'install_date' => $this->install_date,
           // 'uninstall_date' => $this->uninstall_date,
           // 'expire_date' => $this->expire_date,
       
            'last_login_time' => $this->last_login_time,
            'last_login_ip' => $this->last_login_ip,
            'maximum_prod_limit' => $this->maximum_prod_limit,
        ]);


        $query->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'install_status', $this->install_status])
            ->andFilterWhere(['like', 'purchase_status', $this->purchase_status])
            ->andFilterWhere(['like', 'last_login_ip', $this->last_login_ip])
            ->andFilterWhere(['like', 'seller_username', $this->seller_username])
            ->andFilterWhere(['like', 'merchant.shopurl', $this->shopurl])
            ->andFilterWhere(['like', 'merchant.shopname', $this->shopname])
            ->andFilterWhere(['like', 'merchant.owner_name', $this->owner_name])
            ->andFilterWhere(['>=', 'install_date' , $this->install_date])
            ->andFilterWhere(['<=', 'install_date' , $this->install_date2])
            ->andFilterWhere(['>=', 'expire_date' , $this->expire_date])
            ->andFilterWhere(['<=', 'expire_date' , $this->expire_date2])
            ->andFilterWhere(['>=', 'uninstall_date' , $this->uninstall_date])
            ->andFilterWhere(['<=', 'uninstall_date' , $this->uninstall_date2])
            ->andFilterWhere(['like', 'merchant.email' , $this->email])
            ->andFilterWhere(['like', 'seller_password', $this->seller_password]);

        return $dataProvider;
    }
}
