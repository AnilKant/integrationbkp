<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Pricefallsshopdetails;
use backend\models\PricefallsInstallation;
use common\models\Merchant;

/**
 * PricefallsshopdetailsSearch represents the model behind the search form about `backend\models\Pricefallsshopdetails`.
 */
class PricefallsshopdetailsSearch extends Pricefallsshopdetails
{
    /**
     * @inheritdoc
     */
    public $install_date2;
    public $expire_date2;
    public $uninstall_date2;
    public $merhcant;
    public $shopurl;
    public $email;
    public $shopname;
    public $status;
    public $onboarding_step;

    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
            [['token', 'install_status','status', 'install_date', 'uninstall_date', 'expire_date', 'purchase_status', 'last_login_IP', 'last_login_time', 'created_at', 'updated_at','email','shopurl','shopname','onboarding_step'], 'safe'],
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
        $query = Pricefallsshopdetails::find()->select('`pricefalls_shop_details`.*,`pricefalls_installation`.`status`');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['install_date' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
             $dataProvider = new ActiveDataProvider;
            return $dataProvider;
        }
        $query->joinWith(['merchant']);
        $query->joinWith(['installationdetail']);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'merchant.merchant_id' => $this->merchant_id,
            'install_date' => $this->install_date,
            /*'merchant.shopurl' => $this->shopurl,
            'merchant.email' => $this->email,
            'merchant.shopname' => $this->shopname,*/
            // 'merchant.status' => $this->status,
            'uninstall_date' => $this->uninstall_date,
            'expire_date' => $this->expire_date,
            'last_login_IP' => $this->last_login_IP,
            'last_login_time' => $this->last_login_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pricefalls_installation.status' => $this->onboarding_step,
        ]);

        $query->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'install_status', $this->install_status])->andFilterWhere(['like', 'merchant.shopurl', $this->shopurl])
            ->andFilterWhere(['like', 'merchant.shopname' , $this->shopname])
            ->andFilterWhere(['like', 'merchant.email' , $this->email])
            ->andFilterWhere(['like', 'purchase_status', $this->purchase_status]);

        return $dataProvider;
    }
    
}
