<?php
/**
 * Class name is ConfigSmslogSearch * @package backend\modules\common\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-07 15:20 
 */

namespace common\models\log;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\log\ConfigSmslog;

/**
 * ConfigSmslogSearch represents the model behind the search form of `common\models\log\ConfigSmslog`.
 */
class ConfigSmslogSearch extends ConfigSmslog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'member_id', 'error_code', 'used', 'use_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['mobile', 'code', 'content', 'error_msg', 'error_data', 'usage', 'ip'], 'safe'],
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
        $query = ConfigSmslog::find();

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
            'member_id' => $this->member_id,
            'error_code' => $this->error_code,
            'used' => $this->used,
            'use_time' => $this->use_time,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'error_msg', $this->error_msg])
            ->andFilterWhere(['like', 'error_data', $this->error_data])
            ->andFilterWhere(['like', 'usage', $this->usage])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
