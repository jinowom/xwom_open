<?php
/**
 * Class name is XportalBaseSearch * @package backend\modules\xportal\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 20:33 
 */

namespace backend\modules\xportal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xportal\models\XportalBase;

/**
 * XportalBaseSearch represents the model behind the search form of `backend\modules\xportal\models\XportalBase`.
 */
class XportalBaseSearch extends XportalBase
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['base_id', 'base_theme_id'], 'integer'],
            [['base_sitename', 'base_url', 'base_keywords', 'base_createtime', 'base_sponser', 'base_description', 'base_address', 'base_zip', 'base_tel', 'base_email', 'base_copyright', 'base_logo', 'base_egname', 'icp'], 'safe'],
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
        $query = XportalBase::find();

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
            'base_id' => $this->base_id,
            'base_createtime' => $this->base_createtime,
            'base_theme_id' => $this->base_theme_id,
        ]);

        $query->andFilterWhere(['like', 'base_sitename', $this->base_sitename])
            ->andFilterWhere(['like', 'base_url', $this->base_url])
            ->andFilterWhere(['like', 'base_keywords', $this->base_keywords])
            ->andFilterWhere(['like', 'base_sponser', $this->base_sponser])
            ->andFilterWhere(['like', 'base_description', $this->base_description])
            ->andFilterWhere(['like', 'base_address', $this->base_address])
            ->andFilterWhere(['like', 'base_zip', $this->base_zip])
            ->andFilterWhere(['like', 'base_tel', $this->base_tel])
            ->andFilterWhere(['like', 'base_email', $this->base_email])
            ->andFilterWhere(['like', 'base_copyright', $this->base_copyright])
            ->andFilterWhere(['like', 'base_logo', $this->base_logo])
            ->andFilterWhere(['like', 'base_egname', $this->base_egname])
            ->andFilterWhere(['like', 'icp', $this->icp]);

        return $dataProvider;
    }
}
