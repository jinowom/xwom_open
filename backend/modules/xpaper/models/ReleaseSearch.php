<?php

namespace backend\modules\xpaper\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xpaper\models\Release;

/**
 * ReleaseSearch represents the model behind the search form of `backend\modules\xpaper\models\Release`.
 */
class ReleaseSearch extends Release
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['release_id', 'release_status', 'release_site_id', 'release_pagecount', 'release_theme_id', 'release_emphasis', 'cache'], 'integer'],
            [['release_name', 'release_time', 'release_master', 'release_pubtime', 'release_opentime', 'release_total'], 'safe'],
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
        $query = Release::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
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
            'release_id' => $this->release_id,
            'release_status' => $this->release_status,
            'release_site_id' => $this->release_site_id,
            'release_pagecount' => $this->release_pagecount,
            'release_theme_id' => $this->release_theme_id,
            'release_emphasis' => $this->release_emphasis,
            'cache' => $this->cache,
        ]);

        $query->andFilterWhere(['like', 'release_name', $this->release_name])
            ->andFilterWhere(['like', 'release_time', $this->release_time])
            ->andFilterWhere(['like', 'release_master', $this->release_master])
            ->andFilterWhere(['like', 'release_pubtime', $this->release_pubtime])
            ->andFilterWhere(['like', 'release_opentime', $this->release_opentime])
            ->andFilterWhere(['like', 'release_total', $this->release_total]);

        return $dataProvider;
    }
}
