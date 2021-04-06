<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WishShopDetails;
use yii\db\Expression;

/**
 * WishShopDetailsSearch represents the model behind the search form about `backend\models\WishShopDetails`.
 */
class WishShopDetailsSearch extends WishShopDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'install_status', 'purchase_status'], 'integer'],
            [['token', 'install_date', 'uninstall_dates', 'expire_date', 'ip_address', 'last_login', 'updated_at', 'created_at', 'shopurl', 'owner_name', 'email', 'config','plan_type','already_selling','wish_mid'], 'safe'],
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
        $query = WishShopDetails::find()->select(['wish_shop_details.*', 'merchant.shopurl', 'merchant.owner_name', 'merchant.email', 'merchant.status AS user_status', 'merchant.shop_json', 'wish_config.value AS config', 'merchant.country_code', 'merchant.verified_mobile','wish_registration.already_selling']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['attributes'=>['merchant_id','token','install_status','install_date','uninstall_dates','purchase_status','expire_date', 'ip_address', 'last_login', 'updated_at', 'created_at', 'shopurl', 'owner_name', 'email', 'config'], 'defaultOrder' => ['install_date'=>SORT_DESC,'created_at'=>SORT_DESC]]
        ]);

        $query->innerJoin('merchant', '`merchant`.`merchant_id` = `wish_shop_details`.`merchant_id`');


        $query->leftJoin('wish_config', '`wish_config`.`merchant_id` = `wish_shop_details`.`merchant_id` AND `wish_config`.`config_path` = \'merchant_userid\'');

        $query->leftJoin('wish_registration','`wish_registration`.`merchant_id` = `merchant`.`merchant_id`');


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'wish_shop_details.merchant_id' => $this->merchant_id,
            'install_status' => $this->install_status,
            'install_date' => $this->install_date,
            'uninstall_dates' => $this->uninstall_dates,
            'purchase_status' => $this->purchase_status,
            'expire_date' => $this->expire_date,
            'last_login' => $this->last_login,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'wish_registration.already_selling' => $this->already_selling
        ]);

        $query->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address]);

        $query->andFilterWhere(['like', 'merchant.shopurl', $this->shopurl])
              ->andFilterWhere(['like', 'merchant.owner_name', $this->owner_name])
              ->andFilterWhere(['like', 'merchant.email', $this->email]);

        if(!empty($this->config)) {
            if($this->config == 'yes') {
                $query->andFilterWhere(['IS', 'wish_config.value', new Expression('NOT NULL')]);
            } else {
                $query->andFilterWhere(['IS', 'wish_config.value', new Expression('NULL')]);
            }
        }

        if(!empty($this->wish_mid)) {
                $query->andFilterWhere(['=', 'wish_config.value', $this->wish_mid]);
        }
        
        if(!is_null($this->user_status)) {
            $query->andFilterWhere(['=', '`merchant`.`status`', $this->user_status]);
        }

//        var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die;

        return $dataProvider;
    }
}
