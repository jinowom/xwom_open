<?php

namespace backend\modules\xpaper\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xpaper\models\XpSensitiveWord;

/**
 * XpSensitiveWordSearch represents the model behind the search form of `backend\modules\xpaper\models\XpSensitiveWord`.
 */
class XpSensitiveWordSearch extends XpSensitiveWord
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['badword_id', 'siteid', 'status'], 'integer'],
            [['badword'], 'safe'],
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
        $query = XpSensitiveWord::find();

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
            'badword_id' => $this->badword_id,
            'siteid' => $this->siteid,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'badword', $this->badword]);

        return $dataProvider;
    }
}
