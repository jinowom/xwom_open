<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_comment".
 *
 * @property int $id 评论id
 * @property int $newsid 新闻id
 * @property string $newstitle 被评论新闻的标题
 * @property string $discuss_content 评论内容
 * @property string $discuss_ip 评论ip
 * @property string $discuss_time 评论时间
 * @property int $user_id 用户id，0为匿名用户
 * @property int $status 0-未通过审核，1-通过审核
 * @property int $isshow 0-不在页面显示，1-在页面显示
 * @property int $siteid 站点id
 */
class XpComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xp_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['newsid', 'newstitle', 'discuss_content', 'discuss_ip', 'discuss_time'], 'required'],
            [['newsid', 'user_id', 'status', 'isshow', 'siteid'], 'integer'],
            [['newstitle'], 'string', 'max' => 500],
            [['discuss_content'], 'string', 'max' => 1500],
            [['discuss_ip'], 'string', 'max' => 30],
            [['discuss_time'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '评论id'),
            'newsid' => Yii::t('app', '新闻id'),
            'newstitle' => Yii::t('app', '被评论新闻的标题'),
            'discuss_content' => Yii::t('app', '评论内容'),
            'discuss_ip' => Yii::t('app', '评论ip'),
            'discuss_time' => Yii::t('app', '评论时间'),
            'user_id' => Yii::t('app', '用户id，0为匿名用户'),
            'status' => Yii::t('app', '0-未通过审核，1-通过审核'),
            'isshow' => Yii::t('app', '0-不在页面显示，1-在页面显示'),
            'siteid' => Yii::t('app', '站点id'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return XpCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpCommentQuery(get_called_class());
    }
}
