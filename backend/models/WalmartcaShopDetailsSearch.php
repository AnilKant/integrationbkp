<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use backend\models\WalmartcaShopDetails;

/**
 * WalmartcaShopDetailsSearch represents the model behind the search form about `backend\models\WalmartcaShopDetails`.
 */
class WalmartcaShopDetailsSearch extends WalmartcaShopDetails
{
  public $install_date2;
  public $uninstall_date2;
  public $expire_date2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'install_status', 'purchase_status'], 'integer'],
            [['token', 'install_date', 'install_date2','uninstall_dates', 'uninstall_date2', 'expire_date', 'expire_date2', 'ip_address', 'last_login', 'updated_at', 'created_at', 'seller_username', 'seller_password','shopurl','email','verified_mobile','verified_email','config','fname','lname'], 'safe'],
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
        $query = WalmartcaShopDetails::find()->select(['walmartca_shop_details.*','merchant.shopurl','merchant.email','merchant.verified_email','walmartca_registration.fname','walmartca_registration.lname','merchant.verified_mobile','walmartca_configuration_settings.value AS config']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['attributes'=>['id','merchant_id','install_status','install_date','uninstall_dates','purchase_status','expire_date', 'ip_address', 'last_login', 'shopurl', 'email', 'config', 'fname','lname'], 'defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $query->innerJoin('merchant', '`merchant`.`merchant_id` = `walmartca_shop_details`.`merchant_id`');

        $query->leftJoin('walmartca_registration', '`walmartca_registration`.`merchant_id` = `walmartca_shop_details`.`merchant_id`');

        $query->leftJoin('walmartca_configuration_settings', '`walmartca_configuration_settings`.`merchant_id` = `walmartca_shop_details`.`merchant_id` AND `walmartca_configuration_settings`.`config_path` = \'consumer_id\'');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'walmartca_shop_details.merchant_id' => $this->merchant_id,
            'install_status' => $this->install_status,
            // 'install_date' => $this->install_date,
            // 'uninstall_dates' => $this->uninstall_dates,
            'purchase_status' => $this->purchase_status,
            // 'expire_date' => $this->expire_date,
            'last_login' => $this->last_login,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like','ip_address',$this->ip_address]);

        $query->andFilterWhere(['like', 'merchant.shopurl', $this->shopurl])
              ->andFilterWhere(['like', 'merchant.email', $this->email])
              ->andFilterWhere(['>=', 'walmartca_shop_details.install_date', $this->install_date])
              ->andFilterWhere(['<=', 'walmartca_shop_details.install_date', $this->install_date2])
              ->andFilterWhere(['>=', 'walmartca_shop_details.uninstall_dates', $this->uninstall_dates])
              ->andFilterWhere(['<=', 'walmartca_shop_details.uninstall_dates', $this->uninstall_date2])
              ->andFilterWhere(['>=', 'walmartca_shop_details.expire_date', $this->expire_date])
              ->andFilterWhere(['<=', 'walmartca_shop_details.expire_date', $this->expire_date2])
              ->andFilterWhere(['like', 'walmartca_registration.fname', $this->fname])
              ->andFilterWhere(['like', 'walmartca_registration.lname', $this->lname]);
              // ->andFilterWhere(['like', 'merchant.email', $this->email]);

          if(!empty($this->config)) {
            if($this->config == 'yes') {
                $query->andFilterWhere(['IS', 'walmartca_configuration_settings.value', new Expression('NOT NULL')]);
            } else {
                $query->andFilterWhere(['IS', 'walmartca_configuration_settings.value', new Expression('NULL')]);
            }
        }

        $query->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'seller_username', $this->seller_username])
            ->andFilterWhere(['like', 'seller_password', $this->seller_password]);

        // var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die;

        return $dataProvider;
    }
}
