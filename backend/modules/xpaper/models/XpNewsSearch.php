<?php

namespace backend\modules\xpaper\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xpaper\models\XpNews;

/**
 * XpNewsSearch represents the model behind the search form of `backend\modules\xpaper\models\XpNews`.
 */
class XpNewsSearch extends XpNews
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_num', 'special_order', 'emphasis_order', 'emphasis', 'imagesnumbers', 'type', 'paper_id', 'release_id', 'site_id', 'checkserche', 'paging_type', 'maxcharperpage', 'maxcharimge', 'searchid', 'yes_no_islink', 'isdata', 'paper_order', 'animation_takeaway', 'cache', 'click_number', 'like_number', 'status'], 'integer'],
            [['special_id', 'title_eyebrow', 'title', 'title_sub', 'author', 'class', 'articleclass', 'resource', 'foreword', 'keywords', 'content', 'created_at', 'updated_at', 'uploadfile', 'movie_uir', 'islink', 'coordinate', 'canvas_type'], 'safe'],
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
        $query = XpNews::find();

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
            'order_num' => $this->order_num,
            'special_order' => $this->special_order,
            'emphasis_order' => $this->emphasis_order,
            'emphasis' => $this->emphasis,
            'imagesnumbers' => $this->imagesnumbers,
            'type' => $this->type,
            'paper_id' => $this->paper_id,
            'release_id' => $this->release_id,
            'site_id' => $this->site_id,
            'checkserche' => $this->checkserche,
            'paging_type' => $this->paging_type,
            'maxcharperpage' => $this->maxcharperpage,
            'maxcharimge' => $this->maxcharimge,
            'searchid' => $this->searchid,
            'yes_no_islink' => $this->yes_no_islink,
            'isdata' => $this->isdata,
            'paper_order' => $this->paper_order,
            'animation_takeaway' => $this->animation_takeaway,
            'cache' => $this->cache,
            'click_number' => $this->click_number,
            'like_number' => $this->like_number,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'special_id', $this->special_id])
            ->andFilterWhere(['like', 'title_eyebrow', $this->title_eyebrow])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'title_sub', $this->title_sub])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'articleclass', $this->articleclass])
            ->andFilterWhere(['like', 'resource', $this->resource])
            ->andFilterWhere(['like', 'foreword', $this->foreword])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'uploadfile', $this->uploadfile])
            ->andFilterWhere(['like', 'movie_uir', $this->movie_uir])
            ->andFilterWhere(['like', 'islink', $this->islink])
            ->andFilterWhere(['like', 'coordinate', $this->coordinate])
            ->andFilterWhere(['like', 'canvas_type', $this->canvas_type]);

        return $dataProvider;
    }
}
