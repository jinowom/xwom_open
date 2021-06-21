<?php
/**
 * Class name is ConfigFunctionlogSearch * @package backend\modules\common\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-04-14 23:27 
 */

namespace common\models\log;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\log\ConfigFunctionlog;

/**
 * ConfigFunctionlogSearch represents the model behind the search form about `common\models\log\ConfigFunctionlog`.
 */
class ConfigFunctionlogSearch extends ConfigFunctionlog
{
    const EMPTY_STRING = "(空字符)";
    const NO_EMPTY = "(非空)";
    const SCENARIO_EDITABLE = 'editable';

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_EDITABLE => [],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'user_id', 'error_code'], 'integer'],
            [['app_id', 'method', 'module', 'controller', 'action', 'url', 'get_data', 'post_data', 'header_data', 'ip', 'error_msg', 'error_data', 'req_id', 'user_agent', 'device', 'device_uuid', 'device_version', 'device_app_version', 'status'], 'safe'],
            [['created_at', 'updated_at'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ];
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
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();
        $this->load($params);
        if ( ! is_null($this->created_at) && strpos($this->created_at, ' - ') !== false ) {
            list($s, $e) = explode(' - ', $this->created_at);
            $query->andFilterWhere(['between', 'created_at', strtotime($s), strtotime($e)]);
        }
        if ( ! is_null($this->updated_at) && strpos($this->updated_at, ' - ') !== false ) {
            list($s, $e) = explode(' - ', $this->updated_at);
            $query->andFilterWhere(['between', 'updated_at', strtotime($s), strtotime($e)]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'merchant_id' => $this->merchant_id,
            'user_id' => $this->user_id,
            'error_code' => $this->error_code,
        ]);
        $this->filterLike($query, 'app_id');
        $this->filterLike($query, 'method');
        $this->filterLike($query, 'module');
        $this->filterLike($query, 'controller');
        $this->filterLike($query, 'action');
        $this->filterLike($query, 'url');
        $this->filterLike($query, 'get_data');
        $this->filterLike($query, 'post_data');
        $this->filterLike($query, 'header_data');
        $this->filterLike($query, 'ip');
        $this->filterLike($query, 'error_msg');
        $this->filterLike($query, 'error_data');
        $this->filterLike($query, 'req_id');
        $this->filterLike($query, 'user_agent');
        $this->filterLike($query, 'device');
        $this->filterLike($query, 'device_uuid');
        $this->filterLike($query, 'device_version');
        $this->filterLike($query, 'device_app_version');
        $this->filterLike($query, 'status');;
        $dataProvider = new ActiveDataProvider([
            //'pagination' => ['pageSize' => 3,],
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            ]);
        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }

    /**
     * @param ActiveQuery $query
     * @param $attribute
     */
    protected function filterLike(&$query, $attribute)
    {
        $this->$attribute = trim($this->$attribute);
        switch($this->$attribute){
            case \Yii::t('app', '(not set)'):
                $query->andFilterWhere(['IS', $attribute, new Expression('NULL')]);
                break;
            case self::EMPTY_STRING:
                $query->andWhere([$attribute => '']);
                break;
            case self::NO_EMPTY:
                $query->andWhere(['IS NOT', $attribute, new Expression('NULL')])->andWhere(['<>', $attribute, '']);
                break;
            default:
                $query->andFilterWhere(['like', $attribute, $this->$attribute]);
                break;
        }
    }
}
