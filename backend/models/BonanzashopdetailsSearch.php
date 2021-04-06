<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * bonanzashopdetailsSearch represents the model behind the search form about `backend\models\bonanzashopdetails`.
 */
class BonanzashopdetailsSearch extends Bonanzashopdetails
{
    /**
     * @inheritdoc
     */
    public $install_date2, $expire_date2, $uninstall_date2, $merhcant, $shopurl, $email, $shopname, $verified_mobile,$is_varified;
    public $owner_name;
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'install_status'], 'integer'],
            [['token', 'installed_on', 'uninstall_date', 'expired_on', 'created_at', 'email','shopurl','shopname','seller_username','ip_address','seller_password', 'purchase_status','is_varified','disable_cron','limit_renew_till','owner_name'], 'safe'],
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
        $query = Bonanzashopdetails::find()->select(['`bonanza_shop_details`.*, `bonanza_configuration`. `is_varified`']);

        $query->leftJoin(['bonanza_configuration'], 'bonanza_shop_details.merchant_id = bonanza_configuration.merchant_id');
        // add conditions that should always apply here


        $pageSize = isset($params['per-page']) ? intval($params['per-page']) : 50;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>  ['pageSize' => $pageSize],
            'sort'=> [
                'defaultOrder' => ['installed_on' => SORT_DESC],
                'attributes' => []
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['merchant']);

        if($this->is_varified != '' && $this->is_varified=='yes' ){
            $query->andWhere("bonanza_configuration.is_varified = '" . $this->is_varified."' " );
        }elseif($this->is_varified != '' && $this->is_varified=='no' ){
            $query->andWhere("bonanza_configuration.is_varified is null " );
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'merchant.merchant_id' => $this->merchant_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'shopurl', $this->shopurl])
            ->andFilterWhere(['like', 'token', $this->token])

            ->andFilterWhere(['like', 'merchant.email', $this->email])
            ->andFilterWhere(['like', 'merchant.shopname', $this->shopname])
            ->andFilterWhere(['like', 'merchant.owner_name', $this->owner_name])

            ->andFilterWhere(['like', 'seller_username', $this->seller_username])
            ->andFilterWhere(['like', 'seller_password', $this->seller_password])

            ->andFilterWhere(['like', 'install_status', $this->install_status])
            ->andFilterWhere(['like', 'purchase_status', $this->purchase_status])

            ->andFilterWhere(['like', 'ip_address', $this->ip_address])

            ->andFilterWhere(['like', 'installed_on', $this->installed_on])
            ->andFilterWhere(['like', 'expired_on', $this->expired_on])
            ->andFilterWhere(['like', 'uninstall_date', $this->uninstall_date])

            ->andFilterWhere(['like', 'disable_cron', $this->disable_cron]);
            //die($query->createCommand()->getRawSql());
        return $dataProvider;
    }
  
}
