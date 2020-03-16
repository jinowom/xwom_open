<?php
/**
 * Class name is XportalNewsSearch * @package backend\modules\xportal\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 20:51 
 */

namespace backend\modules\xportal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xportal\models\XportalNews;

/**
 * XportalNewsSearch represents the model behind the search form of `backend\modules\xportal\models\XportalNews`.
 */
class XportalNewsSearch extends XportalNews
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'catid', 'channelid', 'listorder', 'news_checkserche', 'paging_type', 'maxcharperpage', 'maxcharimge', 'allow_comment', 'status', 'yes_no_islink', 'click_number', 'inputime', 'updatetime', 'index_listorder', 'channel_listorder', 'is_image', 'cache', 'ranking_position', 'news_author_id', 'shuffling_index', 'shuffling_channel', 'news_discuss_num', 'siteid'], 'integer'],
            [['title_eyebrow', 'title', 'title_sub', 'content', 'author', 'keywords', 'groupids_view', 'news_uploadfile', 'news_movie_uir', 'movie_blankurl', 'relation', 'copyfrom', 'islink', 'username', 'arrparent_catid', 'update_username', 'rejection_reason', 'use_catid', 'shuffling', 'thumbnail', 'news_uploadfile_describe', 'news_movie_uir_describe'], 'safe'],
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
     * 创建时间  如果不需要或者该数据模型 没有 created_at 字段，您应该删除
     * @return array|false|int
     */
    public function getCreatedAt()
    {
        if(empty($this->created_at)){
            return null;
        }
        $createAt = is_string($this->created_at)?strtotime($this->created_at):$this->created_at;
        if(date('H:i:s', $createAt)=='00:00:00'){
            return [$createAt, $createAt+3600*24];
        }
        return $createAt;
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
        $query = XportalNews::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'pagination' => ['pageSize' => 10,],
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
            'catid' => $this->catid,
            'channelid' => $this->channelid,
            'listorder' => $this->listorder,
            'news_checkserche' => $this->news_checkserche,
            'paging_type' => $this->paging_type,
            'maxcharperpage' => $this->maxcharperpage,
            'maxcharimge' => $this->maxcharimge,
            'allow_comment' => $this->allow_comment,
            'status' => $this->status,
            'yes_no_islink' => $this->yes_no_islink,
            'click_number' => $this->click_number,
            'inputime' => $this->inputime,
            'updatetime' => $this->updatetime,
            'index_listorder' => $this->index_listorder,
            'channel_listorder' => $this->channel_listorder,
            'is_image' => $this->is_image,
            'cache' => $this->cache,
            'ranking_position' => $this->ranking_position,
            'news_author_id' => $this->news_author_id,
            'shuffling_index' => $this->shuffling_index,
            'shuffling_channel' => $this->shuffling_channel,
            'news_discuss_num' => $this->news_discuss_num,
            'siteid' => $this->siteid,
        ]);

        $query->andFilterWhere(['like', 'title_eyebrow', $this->title_eyebrow])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'title_sub', $this->title_sub])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'groupids_view', $this->groupids_view])
            ->andFilterWhere(['like', 'news_uploadfile', $this->news_uploadfile])
            ->andFilterWhere(['like', 'news_movie_uir', $this->news_movie_uir])
            ->andFilterWhere(['like', 'movie_blankurl', $this->movie_blankurl])
            ->andFilterWhere(['like', 'relation', $this->relation])
            ->andFilterWhere(['like', 'copyfrom', $this->copyfrom])
            ->andFilterWhere(['like', 'islink', $this->islink])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'arrparent_catid', $this->arrparent_catid])
            ->andFilterWhere(['like', 'update_username', $this->update_username])
            ->andFilterWhere(['like', 'rejection_reason', $this->rejection_reason])
            ->andFilterWhere(['like', 'use_catid', $this->use_catid])
            ->andFilterWhere(['like', 'shuffling', $this->shuffling])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'news_uploadfile_describe', $this->news_uploadfile_describe])
            ->andFilterWhere(['like', 'news_movie_uir_describe', $this->news_movie_uir_describe]);

        return $dataProvider;
    }
}
