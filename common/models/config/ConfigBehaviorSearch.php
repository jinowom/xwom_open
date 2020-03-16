<?php
/**
 * Class name is ConfigBehaviorSearch * @package backend\modules\common\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-06 18:47 
 */

namespace common\models\config;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\config\ConfigBehavior;

/**
 * ConfigBehaviorSearch represents the model behind the search form of `common\models\config\ConfigBehavior`.
 */
class ConfigBehaviorSearch extends ConfigBehavior
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'action', 'is_record_post', 'is_ajax', 'is_addon', 'status', 'created_at', 'updated_at', 'created_id', 'updated_id'], 'integer'],
            [['app_id', 'url', 'method', 'behavior', 'level', 'remark', 'addons_name'], 'safe'],
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
        $query = ConfigBehavior::find();

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
            'action' => $this->action,
            'is_record_post' => $this->is_record_post,
            'is_ajax' => $this->is_ajax,
            'is_addon' => $this->is_addon,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_id' => $this->created_id,
            'updated_id' => $this->updated_id,
        ]);

        $query->andFilterWhere(['like', 'app_id', $this->app_id])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'behavior', $this->behavior])
            ->andFilterWhere(['like', 'level', $this->level])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'addons_name', $this->addons_name]);

        return $dataProvider;
    }
}
