<?php
/**
 * Class name is XportalChannelSearch * @package backend\modules\xportal\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 18:10 
 */

namespace backend\modules\xportal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xportal\models\XportalChannel;

/**
 * XportalChannelSearch represents the model behind the search form of `backend\modules\xportal\models\XportalChannel`.
 */
class XportalChannelSearch extends XportalChannel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['channel_id', 'channel_listorder', 'channel_theme_type', 'channel_type', 'channel_theme_id', 'ismenu', 'index_ismenu', 'parameter', 'cache', 'app_sort', 'channel_top', 'siteid', 'app_channel_theme'], 'integer'],
            [['channel_ch_name', 'channel_en_name', 'channel_alias', 'channel_description', 'bank_url', 'pic', 'app_ismenu', 'default_subscribe_channel'], 'safe'],
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
        $query = XportalChannel::find();

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
            'channel_id' => $this->channel_id,
            'channel_listorder' => $this->channel_listorder,
            'channel_theme_type' => $this->channel_theme_type,
            'channel_type' => $this->channel_type,
            'channel_theme_id' => $this->channel_theme_id,
            'ismenu' => $this->ismenu,
            'index_ismenu' => $this->index_ismenu,
            'parameter' => $this->parameter,
            'cache' => $this->cache,
            'app_sort' => $this->app_sort,
            'channel_top' => $this->channel_top,
            'siteid' => $this->siteid,
            'app_channel_theme' => $this->app_channel_theme,
        ]);

        $query->andFilterWhere(['like', 'channel_ch_name', $this->channel_ch_name])
            ->andFilterWhere(['like', 'channel_en_name', $this->channel_en_name])
            ->andFilterWhere(['like', 'channel_alias', $this->channel_alias])
            ->andFilterWhere(['like', 'channel_description', $this->channel_description])
            ->andFilterWhere(['like', 'bank_url', $this->bank_url])
            ->andFilterWhere(['like', 'pic', $this->pic])
            ->andFilterWhere(['like', 'app_ismenu', $this->app_ismenu])
            ->andFilterWhere(['like', 'default_subscribe_channel', $this->default_subscribe_channel]);

        return $dataProvider;
    }
}
