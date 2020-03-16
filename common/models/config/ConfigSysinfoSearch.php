<?php
/**
 * Class name is ConfigSysinfoSearch * @package backend\modules\common\controllers;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-05 16:44 
 */

namespace common\models\config;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\config\ConfigSysinfo;

/**
 * ConfigSysinfoSearch represents the model behind the search form of `common\models\config\ConfigSysinfo`.
 */
class ConfigSysinfoSearch extends ConfigSysinfo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['siteid', 'smarty_id', 'smarty_app_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'dirname', 'domain', 'serveralias', 'keywords', 'description', 'site_point', 'address', 'zipcode', 'tel', 'fax', 'email', 'copyright', 'logo', 'banner', 'reg_time', 'begin_time', 'end_time', 'basemail', 'mailpwd', 'record', 'default_style', 'contacts', 'comp_invoice', 'comp_bank', 'bank_numb'], 'safe'],
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
        $query = ConfigSysinfo::find();

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
            'siteid' => $this->siteid,
            'smarty_id' => $this->smarty_id,
            'smarty_app_id' => $this->smarty_app_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'dirname', $this->dirname])
            ->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['like', 'serveralias', $this->serveralias])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'site_point', $this->site_point])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'zipcode', $this->zipcode])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'copyright', $this->copyright])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'banner', $this->banner])
            ->andFilterWhere(['like', 'reg_time', $this->reg_time])
            ->andFilterWhere(['like', 'begin_time', $this->begin_time])
            ->andFilterWhere(['like', 'end_time', $this->end_time])
            ->andFilterWhere(['like', 'basemail', $this->basemail])
            ->andFilterWhere(['like', 'mailpwd', $this->mailpwd])
            ->andFilterWhere(['like', 'record', $this->record])
            ->andFilterWhere(['like', 'default_style', $this->default_style])
            ->andFilterWhere(['like', 'contacts', $this->contacts])
            ->andFilterWhere(['like', 'comp_invoice', $this->comp_invoice])
            ->andFilterWhere(['like', 'comp_bank', $this->comp_bank])
            ->andFilterWhere(['like', 'bank_numb', $this->bank_numb]);

        return $dataProvider;
    }
}
