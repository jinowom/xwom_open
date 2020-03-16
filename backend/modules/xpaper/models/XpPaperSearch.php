<?php

namespace backend\modules\xpaper\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xpaper\models\XpPaper;

/**
 * XpPaperSearch represents the model behind the search form of `backend\modules\xpaper\models\XpPaper`.
 */
class XpPaperSearch extends XpPaper
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'release_id', 'site_id', 'editor_id', 'filesize', 'cache', 'status'], 'integer'],
            [['name_cn', 'editionnumber', 'url', 'pdf', 'editor', 'created_at', 'updated_at', 'html_url'], 'safe'],
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
        $query = XpPaper::find();

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
            'release_id' => $this->release_id,
            'site_id' => $this->site_id,
            'editor_id' => $this->editor_id,
            'filesize' => $this->filesize,
            'cache' => $this->cache,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name_cn', $this->name_cn])
            ->andFilterWhere(['like', 'editionnumber', $this->editionnumber])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'pdf', $this->pdf])
            ->andFilterWhere(['like', 'editor', $this->editor])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'html_url', $this->html_url]);

        return $dataProvider;
    }
}
