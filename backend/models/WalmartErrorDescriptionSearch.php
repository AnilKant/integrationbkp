<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WalmartErrorDescription;
use frontend\modules\walmart\components\Product\ProductHelper;
use frontend\modules\walmart\components\TableConstant;

/**
 * WalmartErrorDescriptionSearch represents the model behind the search form about `backend\models\WalmartErrorDescription`.
 */
class WalmartErrorDescriptionSearch extends WalmartErrorDescription
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_enable'], 'integer'],
            [['error_code', 'error_description', 'error_solution','error_type'], 'safe'],
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
        $query = WalmartErrorDescription::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $errorKey = [];
        if ($this->error_type)
        {
            $error_array =
                [
                    'Product Error' => [
                        ProductHelper::PRODUCT_ERROR
                    ],
                    'Inventory Error' => [
                        ProductHelper::INVENTORY_ERROR
                    ],
                    'Price Error' => [
                        ProductHelper::PRICE_ERROR
                    ],
                    'Product Retire Error' => [
                        ProductHelper::RETIRE_ERROR
                    ],
                    'Order Error' => [
                        ProductHelper::ORDER_ERROR
                    ],
                ];
            $error_type = $this->error_type;
            if (array_key_exists($error_type, $error_array))
            {
                $errorKey = $error_array[$error_type];
            }
        } else {
            $errorKey = ProductHelper::PRODUCT_ERROR;
        }

        if (is_array($errorKey)) {
            $error = implode(',', $errorKey);
            $query->andFilterWhere(['like', 'error_type', $error]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            // 'error_type' => $this->error_type,
            'is_enable' => $this->is_enable,
        ]);

        $query->andFilterWhere(['like', 'error_code', $this->error_code])
            ->andFilterWhere(['like', 'error_description', $this->error_description])
            ->andFilterWhere(['like', 'error_solution', $this->error_solution]);

        // var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);die;
        return $dataProvider;
    }
}
