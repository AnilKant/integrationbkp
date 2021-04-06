<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Newappmerchantdetails;

class NewappmerchantdetailsSearch extends Newappmerchantdetails
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
        $query = Newappmerchantdetails::find();
        $query->select(['`merchant_db`.*,`merchant`.`status` as `user_status`']);
        $query->innerJoin('merchant', 'merchant.merchant_id = merchant_db.merchant_id');

        // add conditions that should always apply here

        $pageSize = isset($params['per-page']) ? intval($params['per-page']) : 50;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>  ['pageSize' => $pageSize],
            //'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'merchant_db.merchant_id' => $this->merchant_id,
        ]);
        $query->andFilterWhere([
            'merchant_db.app_name' => $this->app_name,
        ]);

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name]);

        if(!is_null($this->user_status)) {
            $query->andFilterWhere(['=', '`user`.`status`', $this->user_status]);
        }
        
        return $dataProvider;
    }
}