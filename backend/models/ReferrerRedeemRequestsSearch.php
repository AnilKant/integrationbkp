<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ReferrerRedeemRequests;

/**
 * ReferrerRedeemRequestsSearch represents the model behind the search form about `backend\models\ReferrerRedeemRequests`.
 */
class ReferrerRedeemRequestsSearch extends ReferrerRedeemRequests
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'referrer_id'], 'integer'],
            [['amount'], 'number'],
            [['redeem_method', 'data', 'status', 'created_at', 'updated_at', 'merchant_id', 'user_username', 'user_shopname', 'referrer_username'], 'safe'],
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
        $query = ReferrerRedeemRequests::find()->select('`referrer_redeem_requests`.*, `referrer_user`.`merchant_id`, `user`.`username` as user_username, `user`.`shop_name` as user_shopname, `referrer_user`.`username` as referrer_username');

        $referrer_id = Yii::$app->request->get('referrer_id', false);
        if($referrer_id) {
            $query->where(['referrer_id' => $referrer_id]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->leftJoin('referrer_user', 'referrer_user.id = referrer_redeem_requests.referrer_id');

        $query->leftJoin('user', 'user.id = referrer_user.merchant_id');

        $query->andFilterWhere([
            'id' => $this->id,
            'referrer_id' => $this->referrer_id,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'redeem_method', $this->redeem_method])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'status', $this->status]);

        $query->andFilterWhere(['=', 'referrer_user.merchant_id', $this->merchant_id])
              ->andFilterWhere(['like', '`user`.`username`', $this->user_username])
              ->andFilterWhere(['like', '`user`.`shop_name`', $this->user_shopname])
              ->andFilterWhere(['like', '`referrer_user`.`username`', $this->referrer_username]);

        //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();

        return $dataProvider;
    }
}
