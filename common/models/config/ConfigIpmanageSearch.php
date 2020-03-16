<?php
/**
 * Class name is ConfigIpmanageSearch * @package backend\modules\common\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-06 18:55 
 */

namespace common\models\config;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\config\ConfigIpmanage;

/**
 * ConfigIpmanageSearch represents the model behind the search form of `common\models\config\ConfigIpmanage`.
 */
class ConfigIpmanageSearch extends ConfigIpmanage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'start_time', 'end_time', 'created_at', 'updated_at', 'created_id', 'updated_id'], 'integer'],
            [['ip'], 'safe'],
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
        $query = ConfigIpmanage::find();

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
            'status' => $this->status,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_id' => $this->created_id,
            'updated_id' => $this->updated_id,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
