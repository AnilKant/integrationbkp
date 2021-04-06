<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Oldappmerchantdetails;

class OldappmerchantdetailsSearch extends Oldappmerchantdetails
{
    public $user_status;
 
	public function rules()
    {
        return [
            [[ 'merchant_id'], 'integer'],
            [['shop_name','merchant_id','app_name'], 'safe'],
        ];
    }
    public function setCustomWhere($where){
        $this->customWhere = $where;
    }

    public function search($params)
    {
            $query = Oldappmerchantdetails::find();
        $query->select(['`merchant_db`.*,`user`.`status` as `user_status`']);
        $query->innerJoin('user', 'user.id = merchant_db.merchant_id');

        // add conditions that should always apply here

        $pageSize = isset($params['per-page']) ? intval($params['per-page']) : 50;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>  ['pageSize' => $pageSize],
            //'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

       // $query->innerJoin('user', 'user.id = jet_shop_details.merchant_id');

        // grid filtering conditions
        $query->andFilterWhere([
           // 'id' => $this->id,
            'merchant_id' => $this->merchant_id,
            //'installed_on' => $this->installed_on,
            //'expired_on' => $this->expired_on,
            //'purchased_on' => $this->purchased_on,
        ]);

        $query->andFilterWhere(['like', '`merchant_db`.`shop_name`', $this->shop_name]);
        $query->andFilterWhere(['like', 'app_name', $this->app_name]);

        if(!is_null($this->user_status)) {
            $query->andFilterWhere(['=', '`user`.`status`', $this->user_status]);
        }
        
       
         //var_dump($query->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql);die();
        //var_dump($dataProvider->getModels());die('hh');
        return $dataProvider;
    }
}