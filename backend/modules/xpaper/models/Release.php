<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_release".
 *
 * @property int $release_id 期次id
 * @property string $release_name 期次名
 * @property string $release_time 出版时间
 * @property string $release_master 主编
 * @property string $release_pubtime 发布时间
 * @property string $release_opentime 公开时间
 * @property int $release_status 状态  1--发布  维护（审核）--2  存档（封存不公开）--3
 * @property string $release_total 总期次
 * @property int $release_site_id 站点ID
 * @property int $release_pagecount 本期版面总数
 * @property int $release_theme_id 本期次选用模板id  如果为1 则默认站点选择模板
 * @property int $release_emphasis 1 推荐 0 不
 * @property int $cache 缓存
 */
class Release extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xp_release';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['release_status', 'release_site_id', 'release_pagecount', 'release_theme_id', 'release_emphasis', 'cache'], 'integer'],
            [['release_name', 'release_master', 'release_total'], 'string', 'max' => 50],
            [['release_time', 'release_pubtime', 'release_opentime'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'release_id' => Yii::t('app', '期次id'),
            'release_name' => Yii::t('app', '期次名'),
            'release_time' => Yii::t('app', '出版时间'),
            'release_master' => Yii::t('app', '主编'),
            'release_pubtime' => Yii::t('app', '发布时间'),
            'release_opentime' => Yii::t('app', '公开时间'),
            'release_status' => Yii::t('app', '状态  1--发布  维护（审核）--2  存档（封存不公开）--3'),
            'release_total' => Yii::t('app', '总期次'),
            'release_site_id' => Yii::t('app', '站点ID'),
            'release_pagecount' => Yii::t('app', '本期版面总数'),
            'release_theme_id' => Yii::t('app', '本期次选用模板id  如果为1 则默认站点选择模板'),
            'release_emphasis' => Yii::t('app', '1 推荐 0 不'),
            'cache' => Yii::t('app', '缓存'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ReleaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReleaseQuery(get_called_class());
    }
}
