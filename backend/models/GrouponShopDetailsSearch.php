<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\GrouponShopDetails;
use yii\db\Expression;

/**
 * GrouponShopDetailsSearch represents the model behind the search form about `backend\models\GrouponShopDetails`.
 */
class GrouponShopDetailsSearch extends GrouponShopDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'install_status', 'purchase_status'], 'integer'],
            [['token', 'install_date', 'uninstall_date', 'expire_date', /*'ip_address', 'last_login',*/ 'updated_at', 'created_at', 'shopurl', 'owner_name', 'email', 'config'], 'safe'],
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
        $query = GrouponShopDetails::find()->select(['groupon_shop_details.*', 'merchant.shopurl', 'merchant.owner_name', 'merchant.email', 'merchant.status AS user_status', 'merchant.shop_json', 'groupon_configuration.access_token AS config']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['attributes'=>['merchant_id','token','install_status','install_date','uninstall_date','purchase_status','expire_date', /*'ip_address', 'last_login',*/ 'updated_at', 'created_at', 'shopurl', 'owner_name', 'email', 'config'], 'defaultOrder' => ['install_date'=>SORT_DESC]]
        ]);

        $query->innerJoin('merchant', '`merchant`.`merchant_id` = `groupon_shop_details`.`merchant_id`');

        $query->leftJoin('groupon_configuration', '`groupon_configuration`.`merchant_id` = `groupon_shop_details`.`merchant_id`');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'groupon_shop_details.merchant_id' => $this->merchant_id,
            'install_status' => $this->install_status,
            'install_date' => $this->install_date,
            'uninstall_date' => $this->uninstall_date,
            'purchase_status' => $this->purchase_status,
            'expire_date' => $this->expire_date,
            //'last_login' => $this->last_login,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'token', $this->token]);
        //$query->andFilterWhere(['like', 'ip_address', $this->ip_address]);

        $query->andFilterWhere(['like', 'merchant.shopurl', $this->shopurl])
              ->andFilterWhere(['like', 'merchant.owner_name', $this->owner_name])
              ->andFilterWhere(['like', 'merchant.email', $this->email]);

        if(!empty($this->config)) {
            if($this->config == 'yes') {
                $query->andFilterWhere(['IS', 'groupon_configuration.access_token', new Expression('NOT NULL')]);
            } else {
                $query->andFilterWhere(['IS', 'groupon_configuration.access_token', new Expression('NULL')]);
            }
        }
        
        if(!is_null($this->user_status)) {
            $query->andFilterWhere(['=', '`merchant`.`status`', $this->user_status]);
        }

        //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die;

        return $dataProvider;
    }
}
