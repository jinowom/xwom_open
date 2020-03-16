<?php

namespace backend\modules\xpaper\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\xpaper\models\XpSiteBase;

/**
 * XpSiteBaseSearch represents the model behind the search form of `backend\modules\xpaper\models\XpSiteBase`.
 */
class XpSiteBaseSearch extends XpSiteBase
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['siteid', 'appid', 'smarty_id', 'smarty_app_id', 'client_country', 'province', 'city', 'industry', 'cache', 'status', 'site_open', 'created_at', 'updated_at', 'is_show', 'islogin', 'ismempower', 'company_id', 'paymode'], 'integer'],
            [['name', 'dirname', 'domain', 'serveralias', 'keywords', 'description', 'site_point', 'address', 'zipcode', 'tel', 'fax', 'email', 'copyright', 'logo', 'banner', 'reg_time', 'ftp_folder', 'auto_folder', 'begin_time', 'end_time', 'basemail', 'mailpwd', 'record', 'default_style', 'contacts', 'comp_invoice', 'comp_bank', 'bank_numb'], 'safe'],
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
        $query = XpSiteBase::find();

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
            'siteid' => $this->siteid,
            'appid' => $this->appid,
            'smarty_id' => $this->smarty_id,
            'smarty_app_id' => $this->smarty_app_id,
            'client_country' => $this->client_country,
            'province' => $this->province,
            'city' => $this->city,
            'industry' => $this->industry,
            'cache' => $this->cache,
            'status' => $this->status,
            'site_open' => $this->site_open,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_show' => $this->is_show,
            'islogin' => $this->islogin,
            'ismempower' => $this->ismempower,
            'company_id' => $this->company_id,
            'paymode' => $this->paymode,
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
            ->andFilterWhere(['like', 'ftp_folder', $this->ftp_folder])
            ->andFilterWhere(['like', 'auto_folder', $this->auto_folder])
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
