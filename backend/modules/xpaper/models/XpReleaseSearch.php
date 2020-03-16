<?php

namespace backend\modules\xpaper\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xpaper\models\XpRelease;

/**
 * XpReleaseSearch represents the model behind the search form of `backend\modules\xpaper\models\XpRelease`.
 */
class XpReleaseSearch extends XpRelease
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'site_id', 'pagecount', 'theme_id', 'emphasis', 'cache'], 'integer'],
            [['name', 'printitime', 'master', 'pubtime', 'opentime', 'total'], 'safe'],
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
        $query = XpRelease::find();

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
            'status' => $this->status,
            'site_id' => $this->site_id,
            'pagecount' => $this->pagecount,
            'theme_id' => $this->theme_id,
            'emphasis' => $this->emphasis,
            'cache' => $this->cache,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'printitime', $this->printitime])
            ->andFilterWhere(['like', 'master', $this->master])
            ->andFilterWhere(['like', 'pubtime', $this->pubtime])
            ->andFilterWhere(['like', 'opentime', $this->opentime])
            ->andFilterWhere(['like', 'total', $this->total]);

        return $dataProvider;
    }
}
