<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ReferralUser;

/**
 * ReferralUserSearch represents the model behind the search form about `backend\models\ReferralUser`.
 */
class ReferralUserSearch extends ReferralUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'referrer_id', 'merchant_id'], 'integer'],
            [['app', 'status', 'installation_date', 'payment_date', 'user_username', 'user_shopname'], 'safe'],
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
        $query = ReferralUser::find()->select('`referral_user`.*, `user`.`username` as user_username, `user`.`shop_name` as user_shopname');

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

        $query->leftJoin('user', 'user.id = referral_user.merchant_id');

        $query->andFilterWhere([
            'id' => $this->id,
            'referrer_id' => $this->referrer_id,
            'merchant_id' => $this->merchant_id,
            'installation_date' => $this->installation_date,
            'payment_date' => $this->payment_date,
        ]);

        $query->andFilterWhere(['like', 'app', $this->app])
            ->andFilterWhere(['like', 'status', $this->status]);

        $query->andFilterWhere(['like', '`user`.`username`', $this->user_username])
              ->andFilterWhere(['like', '`user`.`shop_name`', $this->user_shopname]);
        //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();
        return $dataProvider;
    }
}
