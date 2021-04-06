<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Tophattershopdetails;
use common\models\Merchant;

/**
 * TophattershopdetailsSearch represents the model behind the search form about `backend\models\Tophattershopdetails`.
 */
class TophattershopdetailsSearch extends Tophattershopdetails
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
    public $config_path;
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'install_status', 'purchase_status'], 'integer'],
            [['token', 'install_date', 'uninstall_dates', 'expire_date', 'created_at', 'updated_at','email','shopurl','shopname','config_path'], 'safe'],
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
        // $query = Tophattershopdetails::find();

         $query = Tophattershopdetails::find()->select(['`tophatter_shop_details`.*, `tophatter_installation`. `step`']);

         $query->leftJoin(['tophatter_installation'], 'tophatter_shop_details.merchant_id = tophatter_installation.merchant_id');

        /* $query = Tophattershopdetails::find()->select(['`tophatter_shop_details`.*, `tophatter_configuration_setting`.`config_path`']);

          $query->leftJoin(['tophatter_configuration_setting'], 'tophatter_shop_details.merchant_id = tophatter_configuration_setting.merchant_id');*/
         
         // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['merchant']);
       if($this->config_path != '' && $this->config_path=='yes'){

            $query->andWhere("tophatter_installation.step > 2");
        }elseif($this->config_path != '' && $this->config_path=='no'){
           
            $query->andWhere("tophatter_installation.step < 2");
        }

        /* if($this->config_path != '' && $this->config_path=='yes'){

            $query->andWhere("tophatter_configuration_setting.config_path='access_token'");
        }elseif($this->config_path != '' && $this->config_path=='no'){
           $query->andWhere("tophatter_configuration_setting.config_path!='access_token'");
        }*/

        // echo $query->createCommand()->getRawSql();die;

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'merchant.merchant_id' => $this->merchant_id,
            'install_status' => $this->install_status,
            /*'merchant.shopurl' => $this->shopurl,
            'merchant.email' => $this->email,
            'merchant.shopname' => $this->shopname,*/
            'install_date' => $this->install_date,
            'uninstall_dates' => $this->uninstall_dates,
            'purchase_status' => $this->purchase_status,
            'expire_date' => $this->expire_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'token', $this->token])
        ->andFilterWhere(['like', 'merchant.shopurl', $this->shopurl])
            ->andFilterWhere(['like', 'merchant.shopname' , $this->shopname])
            ->andFilterWhere(['like', 'merchant.email' , $this->email]);

            // print_r($dataProvider);die("abc");
        return $dataProvider;
    }
   
}
