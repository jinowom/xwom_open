<?php

namespace backend\modules\xpaper\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xpaper\models\XpIpmanage;

/**
 * XpIpmanageSearch represents the model behind the search form of `backend\modules\xpaper\models\XpIpmanage`.
 */
class XpIpmanageSearch extends XpIpmanage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'siteid', 'status'], 'integer'],
            [['ip'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = XpIpmanage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'pagination' => ['pageSize' => 1,],
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'siteid' => $this->siteid,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
