<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\NotificationMail;

/**
 * NotificationMailSearch represents the model behind the search form about `backend\models\NotificationMail`.
 */
class NotificationMailSearch extends NotificationMail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'send_mail'], 'integer'],
            [['mail_type', 'days', 'marketplace', 'email_template','subject'], 'safe'],
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
        $query = NotificationMail::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'send_mail' => $this->send_mail,
        ]);

        $query->andFilterWhere(['like', 'mail_type', $this->mail_type])
            ->andFilterWhere(['like', 'days', $this->days])
            ->andFilterWhere(['like', 'marketplace', $this->marketplace])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'email_template', $this->email_template]);

        return $dataProvider;
    }
}
