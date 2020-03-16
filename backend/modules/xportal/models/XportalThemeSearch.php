<?php
/**
 * Class name is XportalThemeSearch * @package backend\modules\xportal\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 18:10 
 */

namespace backend\modules\xportal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xportal\models\XportalTheme;

/**
 * XportalThemeSearch represents the model behind the search form of `backend\modules\xportal\models\XportalTheme`.
 */
class XportalThemeSearch extends XportalTheme
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['theme_id', 'typeid', 'big_type_id', 'theme_listorder', 'index_theme', 'siteid', 'created_id', 'updated_id', 'updated_at', 'created_at'], 'integer'],
            [['theme_name', 'theme_dir', 'theme_image', 'description'], 'safe'],
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
        $query = XportalTheme::find();

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
            'theme_id' => $this->theme_id,
            'typeid' => $this->typeid,
            'big_type_id' => $this->big_type_id,
            'theme_listorder' => $this->theme_listorder,
            'index_theme' => $this->index_theme,
            'siteid' => $this->siteid,
            'created_id' => $this->created_id,
            'updated_id' => $this->updated_id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'theme_name', $this->theme_name])
            ->andFilterWhere(['like', 'theme_dir', $this->theme_dir])
            ->andFilterWhere(['like', 'theme_image', $this->theme_image])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
