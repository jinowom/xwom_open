<?php

namespace backend\modules\xpaper\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xpaper\models\XpSpecial;

/**
 * XpSpecialSearch represents the model behind the search form of `backend\modules\xpaper\models\XpSpecial`.
 */
class XpSpecialSearch extends XpSpecial
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'siteid', 'ishtml', 'ispage', 'adminid', 'userid', 'listorder', 'elite', 'status', 'cache'], 'integer'],
            [['title', 'thumb', 'banner', 'description', 'url', 'filename', 'createtime'], 'safe'],
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
        $query = XpSpecial::find();

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
            'ishtml' => $this->ishtml,
            'ispage' => $this->ispage,
            'adminid' => $this->adminid,
            'userid' => $this->userid,
            'listorder' => $this->listorder,
            'elite' => $this->elite,
            'status' => $this->status,
            'cache' => $this->cache,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'thumb', $this->thumb])
            ->andFilterWhere(['like', 'banner', $this->banner])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'createtime', $this->createtime]);

        return $dataProvider;
    }
}
