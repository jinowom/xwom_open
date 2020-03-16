<?php

namespace backend\modules\xpaper\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xpaper\models\XpTheme;

/**
 * XpThemeSearch represents the model behind the search form of `backend\modules\xpaper\models\XpTheme`.
 */
class XpThemeSearch extends XpTheme
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['theme_id', 'theme_listorder', 'theme_paper_width', 'siteid', 'theme_style', 'status', 'public'], 'integer'],
            [['theme_name', 'theme_dir', 'theme_image', 'description', 'theme_html_en'], 'safe'],
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
        $query = XpTheme::find();

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
            'theme_id' => $this->theme_id,
            'theme_listorder' => $this->theme_listorder,
            'theme_paper_width' => $this->theme_paper_width,
            'siteid' => $this->siteid,
            'theme_style' => $this->theme_style,
            'status' => $this->status,
            'public' => $this->public,
        ]);

        $query->andFilterWhere(['like', 'theme_name', $this->theme_name])
            ->andFilterWhere(['like', 'theme_dir', $this->theme_dir])
            ->andFilterWhere(['like', 'theme_image', $this->theme_image])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'theme_html_en', $this->theme_html_en]);

        return $dataProvider;
    }
}
