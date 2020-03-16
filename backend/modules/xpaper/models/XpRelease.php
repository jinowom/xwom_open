<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_release".
 *
 * @property int $id 期次id
 * @property string $name 期次名
 * @property string $printitime 出版时间
 * @property string $master 主编
 * @property string $pubtime 发布时间
 * @property string $opentime 公开时间
 * @property int $status 状态  1--发布  维护（审核）--2  存档（封存不公开）--3
 * @property string $total 总期次
 * @property int $site_id 站点ID
 * @property int $pagecount 本期版面总数
 * @property int $theme_id 本期次选用模板id  如果为1 则默认站点选择模板
 * @property int $emphasis 1 推荐 0 不
 * @property int $cache 缓存
 */
class XpRelease extends \yii\db\ActiveRecord
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
            [['status', 'site_id', 'pagecount', 'theme_id', 'emphasis', 'cache'], 'integer'],
            [['name', 'master', 'total'], 'string', 'max' => 50],
            [['printitime', 'pubtime', 'opentime'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '期次id'),
            'name' => Yii::t('app', '期次名'),
            'printitime' => Yii::t('app', '出版时间'),
            'master' => Yii::t('app', '主编'),
            'pubtime' => Yii::t('app', '发布时间'),
            'opentime' => Yii::t('app', '公开时间'),
            'status' => Yii::t('app', '状态  1--发布  维护（审核）--2  存档（封存不公开）--3'),
            'total' => Yii::t('app', '总期次'),
            'site_id' => Yii::t('app', '站点ID'),
            'pagecount' => Yii::t('app', '本期版面总数'),
            'theme_id' => Yii::t('app', '本期次选用模板id  如果为1 则默认站点选择模板'),
            'emphasis' => Yii::t('app', '1 推荐 0 不'),
            'cache' => Yii::t('app', '缓存'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return XpReleaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpReleaseQuery(get_called_class());
    }
}
