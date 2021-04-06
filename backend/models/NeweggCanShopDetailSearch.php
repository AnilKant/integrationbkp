<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\NeweggCanShopDetail;
use backend\components\Data;
use yii\db\Expression;

/**
 * NeweggShopDetailSearch represents the model behind the search form about `backend\models\NeweggShopDetail`.
 */
class NeweggCanShopDetailSearch extends NeweggCanShopDetail
{
    public $user_status;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id'], 'integer'],
            [['shop_url', 'shop_name', 'email', 'token', 'country_code', 'currency', 'install_status', 'install_date', 'expire_date', 'purchase_date', 'purchase_status', 'client_data', 'uninstall_date', 'app_status','config','mobile'], 'safe'],
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
        $query = NeweggCanShopDetail::find()->andWhere(['<>','newegg_can_shop_detail.email', 'developer.cedcoss@gmail.com']);

        $query->select(['`newegg_can_shop_detail`.*, `user`.`status` as `user_status`, `user`.`mobile` as `mobile`','newegg_can_configuration.seller_id AS config']);

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

        $query->innerJoin('user', 'user.id = newegg_can_shop_detail.merchant_id');
        $query->leftJoin('newegg_can_configuration', '`newegg_can_configuration`.`merchant_id` = `newegg_can_shop_detail`.`merchant_id` AND `newegg_can_configuration`.`seller_id` IS NOT NULL');

        if(!is_null($this->user_status)) {
            $query->andFilterWhere(['=', '`user`.`status`', $this->user_status]);
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'newegg_can_shop_detail.merchant_id' => $this->merchant_id,
            'install_date' => $this->install_date,
            'expire_date' => $this->expire_date,
            'purchase_date' => $this->purchase_date,
            'uninstall_date' => $this->uninstall_date,
        ]);

        if(!empty($this->config)) {
            if($this->config == 'yes') {
                $query->andFilterWhere(['IS', 'newegg_can_configuration.seller_id', new Expression('NOT NULL')]);
            } else {
                $query->andFilterWhere(['IS', 'newegg_can_configuration.seller_id', new Expression('NULL')]);
            }
        }

        $query->andFilterWhere(['like', 'newegg_can_shop_detail.shop_url', $this->shop_url])
            ->andFilterWhere(['like', 'newegg_can_shop_detail.shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'newegg_can_shop_detail.email', $this->email])
            ->andFilterWhere(['like', 'newegg_can_shop_detail.token', $this->token])
            ->andFilterWhere(['like', 'newegg_can_shop_detail.country_code', $this->country_code])
            ->andFilterWhere(['like', 'newegg_can_shop_detail.currency', $this->currency])
            ->andFilterWhere(['like', 'newegg_can_shop_detail.install_status', $this->install_status])
            ->andFilterWhere(['=', 'newegg_can_shop_detail.purchase_status', $this->purchase_status])
            ->andFilterWhere(['like', 'newegg_can_shop_detail.client_data', $this->client_data])
            ->andFilterWhere(['=', 'newegg_can_shop_detail.app_status', $this->app_status]);

        //var_dump($query->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();
        
        return $dataProvider;
    }

}
