<?php

namespace backend\modules\xedit\models;

use Yii;

/**
 * This is the model class for table "{{%xed_take_papertag}}".
 *
 * @property int $id 主键递增id
 * @property string $news_id 映射p_news的ID
 * @property string $app_id 应用ID，目前仅仅报刊取稿默认为1
 * @property string $pager_id 报纸名ID
 * @property string $page_id 报纸版次ID
 * @property string $use_adminid 取稿人ID
 * @property string $usetime 取稿时间
 * @property string $site_id 稿件所在站点Id
 */
class XedTakePapertag extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xed_take_papertag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id', 'app_id', 'pager_id', 'page_id', 'use_adminid', 'usetime', 'site_id'], 'integer'],
            [['pager_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键递增id',
            'news_id' => '映射p_news的ID',
            'app_id' => '应用ID，目前仅仅报刊取稿默认为1',
            'pager_id' => '报纸名ID',
            'page_id' => '报纸版次ID',
            'use_adminid' => '取稿人ID',
            'usetime' => '取稿时间',
            'site_id' => '稿件所在站点Id',
        ];
    }
}
