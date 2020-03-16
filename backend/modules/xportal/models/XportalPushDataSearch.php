<?php
/**
 * Class name is XportalPushDataSearch * @package backend\modules\xportal\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 20:56 
 */

namespace backend\modules\xportal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xportal\models\XportalPushData;

/**
 * XportalPushDataSearch represents the model behind the search form of `backend\modules\xportal\models\XportalPushData`.
 */
class XportalPushDataSearch extends XportalPushData
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['push_id', 'push_siteid', 'push_news_id', 'push_date', 'push_cms_category', 'push_cms_siteid', 'push_yes_no_islink', 'ifpass'], 'integer'],
            [['push_year', 'push_month', 'push_papername', 'push_issue', 'push_pagename', 'push_title_eyebrow', 'push_title', 'push_title_sub', 'push_author', 'push_foreword', 'push_keywords', 'push_content', 'push_uploadfile', 'push_resource', 'push_islink'], 'safe'],
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
        $query = XportalPushData::find();

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
            'push_id' => $this->push_id,
            'push_siteid' => $this->push_siteid,
            'push_news_id' => $this->push_news_id,
            'push_date' => $this->push_date,
            'push_cms_category' => $this->push_cms_category,
            'push_cms_siteid' => $this->push_cms_siteid,
            'push_yes_no_islink' => $this->push_yes_no_islink,
            'ifpass' => $this->ifpass,
        ]);

        $query->andFilterWhere(['like', 'push_year', $this->push_year])
            ->andFilterWhere(['like', 'push_month', $this->push_month])
            ->andFilterWhere(['like', 'push_papername', $this->push_papername])
            ->andFilterWhere(['like', 'push_issue', $this->push_issue])
            ->andFilterWhere(['like', 'push_pagename', $this->push_pagename])
            ->andFilterWhere(['like', 'push_title_eyebrow', $this->push_title_eyebrow])
            ->andFilterWhere(['like', 'push_title', $this->push_title])
            ->andFilterWhere(['like', 'push_title_sub', $this->push_title_sub])
            ->andFilterWhere(['like', 'push_author', $this->push_author])
            ->andFilterWhere(['like', 'push_foreword', $this->push_foreword])
            ->andFilterWhere(['like', 'push_keywords', $this->push_keywords])
            ->andFilterWhere(['like', 'push_content', $this->push_content])
            ->andFilterWhere(['like', 'push_uploadfile', $this->push_uploadfile])
            ->andFilterWhere(['like', 'push_resource', $this->push_resource])
            ->andFilterWhere(['like', 'push_islink', $this->push_islink]);

        return $dataProvider;
    }
}
