<?php

namespace backend\modules\common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\common\models\WomPlan;

/**
 * WomPlanSearch represents the model behind the search form of `backend\modules\common\models\WomPlan`.
 */
class WomPlanSearch extends WomPlan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'time_status', 'admin_id', 'created_id', 'updated_at', 'updated_id'], 'integer'],
            [['title', 'desc','start_at', 'end_at','created_at'], 'safe'],
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
     * 创建时间
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
     * 开始时间
     * @return array|false|int
     */
    public function getStartAt()
    {
        if(empty($this->start_at)){
            return null;
        }
        $startAt = is_string($this->start_at)?strtotime($this->start_at):$this->start_at;
        if(date('H:i:s', $startAt)=='00:00:00'){
            return [$startAt, $startAt+3600*24];
        }
        return $startAt;
    }
    /**
     * 结束时间
     * @return array|false|int
     */

    public function getEndAt()
    {
        if(empty($this->end_at)){
            return null;
        }
        $endAt = is_string($this->end_at)?strtotime($this->end_at):$this->end_at;
        if(date('H:i:s', $endAt)=='00:00:00'){
            return [$endAt, $endAt+3600*24];
        }
        return $endAt;
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
        $query = WomPlan::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'pagination' => ['pageSize' => 5,],
            //'sort'=>['defaultOrder'=>['id'=>SORT_DESC]],
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
            'time_status' => $this->time_status,
            'admin_id' => $this->admin_id,
            //'start_at' => $this->start_at,
            //'end_at' => $this->end_at,
            //'created_at' => $this->created_at,
            'created_id' => $this->created_id,
            'updated_at' => $this->updated_at,
            'updated_id' => $this->updated_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'desc', $this->desc]);
        
        $createAt = $this->getCreatedAt();
        if(is_array($createAt)) {
            $query->andFilterWhere(['>=','created_at', $createAt[0]])
                ->andFilterWhere(['<=','created_at', $createAt[1]]);
        }else{
            $query->andFilterWhere(['created_at'=>$createAt]);
        }
        $startAt = $this->getStartAt();
        if(is_array($startAt)) {
            $query->andFilterWhere(['>=','start_at', $startAt[0]])
                ->andFilterWhere(['<=','start_at', $startAt[1]]);
        }else{
            $query->andFilterWhere(['start_at'=>$startAt]);
        }
        $endAt = $this->getEndAt();
        if(is_array($endAt)) {
            $query->andFilterWhere(['>=','end_at', $endAt[0]])
                ->andFilterWhere(['<=','end_at', $endAt[1]]);
        }else{
            $query->andFilterWhere(['end_at'=>$endAt]);
        }
        $dataProvider->sort->attributes['authorName'] = 
        [
        	'asc'=>['created_at'=>SORT_ASC],
        	'desc'=>['created_at'=>SORT_DESC],
        ];
        $dataProvider->sort->defaultOrder = 
        [
            'id'=>SORT_DESC,
        ];
        return $dataProvider;
    }
}
