<?php

namespace backend\modules\xpaper\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xpaper\models\XpComment;

/**
 * XpCommentSearch represents the model behind the search form of `backend\modules\xpaper\models\XpComment`.
 */
class XpCommentSearch extends XpComment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'newsid', 'user_id', 'status', 'isshow', 'siteid'], 'integer'],
            [['newstitle', 'discuss_content', 'discuss_ip', 'discuss_time'], 'safe'],
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
        $query = XpComment::find();

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
            'newsid' => $this->newsid,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'isshow' => $this->isshow,
            'siteid' => $this->siteid,
        ]);

        $query->andFilterWhere(['like', 'newstitle', $this->newstitle])
            ->andFilterWhere(['like', 'discuss_content', $this->discuss_content])
            ->andFilterWhere(['like', 'discuss_ip', $this->discuss_ip])
            ->andFilterWhere(['like', 'discuss_time', $this->discuss_time]);

        return $dataProvider;
    }
}
