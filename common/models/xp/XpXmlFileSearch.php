<?php

namespace common\models\xp;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\xp\XpXmlFile;

/**
 * XpXmlFileSearch represents the model behind the search form about `common\models\xp\XpXmlFile`.
 */
class XpXmlFileSearch extends XpXmlFile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'created_id', 'updated_id', 'site_id', 'sortOrder'], 'integer'],
            [['file_name', 'path', 'status', 'is_del'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = XpXmlFile::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_id' => $this->created_id,
            'updated_id' => $this->updated_id,
            'site_id' => $this->site_id,
            'sortOrder' => $this->sortOrder,
        ]);

        $query->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'is_del', $this->is_del]);

        return $dataProvider;
    }
}
