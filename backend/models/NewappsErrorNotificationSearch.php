<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\walmart\components\Product\ProductHelper;
use \yii\db\Query;

/**
 * ErrorNotificationSearch represents the model behind the search form about `backend\models\ErrorNotification`.
 */
class NewappsErrorNotificationSearch extends NewappsErrorNotification
{
    /**
     * @inheritdoc
     */
   
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'error_type'], 'integer'],
            [['identifier', 'identifier_type', 'marketplace', 'reason', 'data', 'created_at', 'updated_at'], 'safe'],
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
        // $query = ErrorNotification::find();
        // $merchant_id = Yii::$app->user->identity->id;
        $str = "`mer`.`status`=1";
        $query = (new Query())
            ->select("COUNT(*) as `error_count`,MAX(`en`.`created_at`) as `created_at`,`en`.`merchant_id`, `en`.`error_type`,`mer`.`shopurl`, `mer`.`email`,`en`.`marketplace`")
            ->from('newapps_error_notification en')
            ->join('INNER JOIN', '`merchant` `mer`' , '`mer`.`merchant_id`=`en`.`merchant_id`')
            ->where($str)
            ->groupBy('en.`merchant_id`');

        $dataProvider = new ActiveDataProvider([
            'db'    => 'admin',
            'query' => $query,
            'sort' => [
                'enableMultiSort' => true,
                'defaultOrder' => ['`en`.`created_at`'=>SORT_DESC],
                'attributes' => ['`en`.`created_at`']
            ]
        ]);

        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'en.merchant_id' => $this->merchant_id,
            'en.error_type' => $this->error_type,
            'en.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'identifier', $this->identifier])
            ->andFilterWhere(['like', 'identifier_type', $this->identifier_type])
            ->andFilterWhere(['like', 'marketplace', $this->marketplace])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'en.created_at', trim($this->created_at)]);

         //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();

        return $dataProvider;
    }
    public static function getDb()
    {
        return Yii::$app->admin;
    }
}
