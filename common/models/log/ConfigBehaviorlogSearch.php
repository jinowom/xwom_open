<?php
/**
 * Class name is ConfigBehaviorlogSearch * @package backend\modules\common\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-07 15:19 
 */

namespace common\models\log;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\log\ConfigBehaviorlog;

/**
 * ConfigBehaviorlogSearch represents the model behind the search form of `common\models\log\ConfigBehaviorlog`.
 */
class ConfigBehaviorlogSearch extends ConfigBehaviorlog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['app_id', 'behavior', 'method', 'module', 'controller', 'action', 'url', 'get_data', 'post_data', 'header_data', 'ip', 'addons_name', 'remark', 'country', 'provinces', 'city', 'device'], 'safe'],
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
        $query = ConfigBehaviorlog::find();

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
            'merchant_id' => $this->merchant_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'app_id', $this->app_id])
            ->andFilterWhere(['like', 'behavior', $this->behavior])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'module', $this->module])
            ->andFilterWhere(['like', 'controller', $this->controller])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'get_data', $this->get_data])
            ->andFilterWhere(['like', 'post_data', $this->post_data])
            ->andFilterWhere(['like', 'header_data', $this->header_data])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'addons_name', $this->addons_name])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'provinces', $this->provinces])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'device', $this->device]);

        return $dataProvider;
    }
}
