<?php
/**
 * Class name is RegWidgetsSearch * @package backend\modules\common\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-07 17:00 
 */

namespace common\models\reg;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\reg\RegWidgets;

/**
 * RegWidgetsSearch represents the model behind the search form of `common\models\reg\RegWidgets`.
 */
class RegWidgetsSearch extends RegWidgets
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_setting', 'is_rule', 'is_merchant_route_map', 'status', 'created_at', 'updated_at', 'created_id', 'updated_id'], 'integer'],
            [['title', 'name', 'title_initial', 'bootstrap', 'service', 'cover', 'brief_introduction', 'description', 'author', 'version', 'default_config', 'console'], 'safe'],
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
        $query = RegWidgets::find();

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
            'is_setting' => $this->is_setting,
            'is_rule' => $this->is_rule,
            'is_merchant_route_map' => $this->is_merchant_route_map,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_id' => $this->created_id,
            'updated_id' => $this->updated_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title_initial', $this->title_initial])
            ->andFilterWhere(['like', 'bootstrap', $this->bootstrap])
            ->andFilterWhere(['like', 'service', $this->service])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'brief_introduction', $this->brief_introduction])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'default_config', $this->default_config])
            ->andFilterWhere(['like', 'console', $this->console]);

        return $dataProvider;
    }
}
