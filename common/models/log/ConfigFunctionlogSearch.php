<?php
/**
 * Class name is ConfigFunctionlogSearch * @package backend\modules\common\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-07 15:17 
 */

namespace common\models\log;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\log\ConfigFunctionlog;

/**
 * ConfigFunctionlogSearch represents the model behind the search form of `common\models\log\ConfigFunctionlog`.
 */
class ConfigFunctionlogSearch extends ConfigFunctionlog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'user_id', 'error_code', 'status', 'created_at', 'updated_at'], 'integer'],
            [['app_id', 'method', 'module', 'controller', 'action', 'url', 'get_data', 'post_data', 'header_data', 'ip', 'error_msg', 'error_data', 'req_id', 'user_agent', 'device', 'device_uuid', 'device_version', 'device_app_version'], 'safe'],
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
        $query = ConfigFunctionlog::find();

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
            'error_code' => $this->error_code,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'app_id', $this->app_id])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'module', $this->module])
            ->andFilterWhere(['like', 'controller', $this->controller])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'get_data', $this->get_data])
            ->andFilterWhere(['like', 'post_data', $this->post_data])
            ->andFilterWhere(['like', 'header_data', $this->header_data])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'error_msg', $this->error_msg])
            ->andFilterWhere(['like', 'error_data', $this->error_data])
            ->andFilterWhere(['like', 'req_id', $this->req_id])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent])
            ->andFilterWhere(['like', 'device', $this->device])
            ->andFilterWhere(['like', 'device_uuid', $this->device_uuid])
            ->andFilterWhere(['like', 'device_version', $this->device_version])
            ->andFilterWhere(['like', 'device_app_version', $this->device_app_version]);

        return $dataProvider;
    }
}
