<?php
/**
 * Class name is XportalCategorySearch * @package backend\modules\xportal\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 18:07 
 */

namespace backend\modules\xportal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xportal\models\XportalCategory;

/**
 * XportalCategorySearch represents the model behind the search form of `backend\modules\xportal\models\XportalCategory`.
 */
class XportalCategorySearch extends XportalCategory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['catid', 'module', 'category_theme', 'temparticle', 'listorder', 'type', 'category_type', 'items', 'hits', 'ismenu', 'parameter', 'sethtml', 'corank', 'siteid', 'cache', 'app_category_theme', 'app_content_theme'], 'integer'],
            [['catname', 'letter', 'alias', 'description', 'bank_url', 'parentdir', 'catdir', 'url', 'setting', 'pic'], 'safe'],
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
        $query = XportalCategory::find();

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
            'catid' => $this->catid,
            'module' => $this->module,
            'category_theme' => $this->category_theme,
            'temparticle' => $this->temparticle,
            'listorder' => $this->listorder,
            'type' => $this->type,
            'category_type' => $this->category_type,
            'items' => $this->items,
            'hits' => $this->hits,
            'ismenu' => $this->ismenu,
            'parameter' => $this->parameter,
            'sethtml' => $this->sethtml,
            'corank' => $this->corank,
            'siteid' => $this->siteid,
            'cache' => $this->cache,
            'app_category_theme' => $this->app_category_theme,
            'app_content_theme' => $this->app_content_theme,
        ]);

        $query->andFilterWhere(['like', 'catname', $this->catname])
            ->andFilterWhere(['like', 'letter', $this->letter])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'bank_url', $this->bank_url])
            ->andFilterWhere(['like', 'parentdir', $this->parentdir])
            ->andFilterWhere(['like', 'catdir', $this->catdir])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'setting', $this->setting])
            ->andFilterWhere(['like', 'pic', $this->pic]);

        return $dataProvider;
    }
}
