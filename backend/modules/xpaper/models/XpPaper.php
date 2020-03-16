<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_paper".
 *
 * @property int $id 版面id
 * @property int $release_id 期次id
 * @property int $site_id 站点ID
 * @property string $name_cn 版面名称	
 * @property string $editionnumber 版次
 * @property string $url JPG路径
 * @property string $pdf pdfl路径
 * @property int $editor_id 本版编辑id
 * @property string $editor 编辑名
 * @property string $created_at 上传时间
 * @property string $updated_at 修改时间
 * @property int $filesize 版面宽度
 * @property string $html_url 静态html 如果为空 则没有生成
 * @property int $cache 缓存
 * @property int $status 审核状态
 */
class XpPaper extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xp_paper';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['release_id', 'site_id', 'editor_id', 'filesize', 'cache', 'status'], 'integer'],
            [['status'], 'required'],
            [['name_cn', 'editor', 'html_url'], 'string', 'max' => 50],
            [['editionnumber'], 'string', 'max' => 100],
            [['url', 'pdf'], 'string', 'max' => 400],
            [['created_at', 'updated_at'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '版面id'),
            'release_id' => Yii::t('app', '期次id'),
            'site_id' => Yii::t('app', '站点ID'),
            'name_cn' => Yii::t('app', '版面名称	'),
            'editionnumber' => Yii::t('app', '版次'),
            'url' => Yii::t('app', 'JPG路径'),
            'pdf' => Yii::t('app', 'pdfl路径'),
            'editor_id' => Yii::t('app', '本版编辑id'),
            'editor' => Yii::t('app', '编辑名'),
            'created_at' => Yii::t('app', '上传时间'),
            'updated_at' => Yii::t('app', '修改时间'),
            'filesize' => Yii::t('app', '版面宽度'),
            'html_url' => Yii::t('app', '静态html 如果为空 则没有生成'),
            'cache' => Yii::t('app', '缓存'),
            'status' => Yii::t('app', '审核状态'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return XpPaperQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpPaperQuery(get_called_class());
    }
}
