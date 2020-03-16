<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_theme".
 *
 * @property int $theme_id 模板id
 * @property string $theme_name 模板名称
 * @property string $theme_dir 模板目录
 * @property string $theme_image 模板平面图目录
 * @property int $theme_listorder 模板排序
 * @property string $description 模板描述
 * @property int $theme_paper_width 版面宽度
 * @property int $siteid 站点id
 * @property int $theme_style 0代表pc模板,1代表app
 * @property string $theme_html_en html全部名
 * @property int $status 状态 1启用； 0 不启用
 * @property int $public 公用状态1公用； 0 不公用
 */
class XpTheme extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xp_theme';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['theme_listorder', 'theme_paper_width', 'siteid', 'theme_style', 'status', 'public'], 'integer'],
            [['status'], 'required'],
            [['theme_name'], 'string', 'max' => 50],
            [['theme_dir', 'theme_image'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 255],
            [['theme_html_en'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'theme_id' => Yii::t('app', '模板id'),
            'theme_name' => Yii::t('app', '模板名称'),
            'theme_dir' => Yii::t('app', '模板目录'),
            'theme_image' => Yii::t('app', '模板平面图目录'),
            'theme_listorder' => Yii::t('app', '模板排序'),
            'description' => Yii::t('app', '模板描述'),
            'theme_paper_width' => Yii::t('app', '版面宽度'),
            'siteid' => Yii::t('app', '站点id'),
            'theme_style' => Yii::t('app', '0代表pc模板,1代表app'),
            'theme_html_en' => Yii::t('app', 'html全部名'),
            'status' => Yii::t('app', '状态 1启用； 0 不启用'),
            'public' => Yii::t('app', '公用状态1公用； 0 不公用'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return XpThemeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpThemeQuery(get_called_class());
    }
}
