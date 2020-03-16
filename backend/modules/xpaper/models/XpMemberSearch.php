<?php

namespace backend\modules\xpaper\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xpaper\models\XpMember;

/**
 * XpMemberSearch represents the model behind the search form of `backend\modules\xpaper\models\XpMember`.
 */
class XpMemberSearch extends XpMember
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'register_time', 'login_time', 'quit_time', 'login_num', 'groupid', 'lock', 'have_message', 'status', 'forbid', 'siteid', 'costtime', 'costendtime'], 'integer'],
            [['user', 'pwd', 'encrypt', 'name', 'realname', 'weixin', 'mobile', 'register_ip', 'last_ip', 'email', 'address', 'address2', 'sex', 'head_portrait_small', 'head_portrait_big', 'qq_id', 'weibo_id', 'forbid_time', 'forbid_keeptime', 'power', 'comp', 'invoice', 'dutynumbe', 'creditnumbe', 'remarks', 'unionid'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = XpMember::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'pagination' => ['pageSize' => 1,],
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
            'register_time' => $this->register_time,
            'login_time' => $this->login_time,
            'quit_time' => $this->quit_time,
            'login_num' => $this->login_num,
            'groupid' => $this->groupid,
            'lock' => $this->lock,
            'have_message' => $this->have_message,
            'status' => $this->status,
            'forbid' => $this->forbid,
            'siteid' => $this->siteid,
            'costtime' => $this->costtime,
            'costendtime' => $this->costendtime,
        ]);

        $query->andFilterWhere(['like', 'user', $this->user])
            ->andFilterWhere(['like', 'pwd', $this->pwd])
            ->andFilterWhere(['like', 'encrypt', $this->encrypt])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'weixin', $this->weixin])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'register_ip', $this->register_ip])
            ->andFilterWhere(['like', 'last_ip', $this->last_ip])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'address2', $this->address2])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'head_portrait_small', $this->head_portrait_small])
            ->andFilterWhere(['like', 'head_portrait_big', $this->head_portrait_big])
            ->andFilterWhere(['like', 'qq_id', $this->qq_id])
            ->andFilterWhere(['like', 'weibo_id', $this->weibo_id])
            ->andFilterWhere(['like', 'forbid_time', $this->forbid_time])
            ->andFilterWhere(['like', 'forbid_keeptime', $this->forbid_keeptime])
            ->andFilterWhere(['like', 'power', $this->power])
            ->andFilterWhere(['like', 'comp', $this->comp])
            ->andFilterWhere(['like', 'invoice', $this->invoice])
            ->andFilterWhere(['like', 'dutynumbe', $this->dutynumbe])
            ->andFilterWhere(['like', 'creditnumbe', $this->creditnumbe])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'unionid', $this->unionid]);

        return $dataProvider;
    }
}
