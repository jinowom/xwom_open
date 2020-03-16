<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_sensitive_word".
 *
 * @property int $badword_id 敏感词id
 * @property string $badword 敏感词内容
 * @property int $siteid 站点id
 * @property int $status 审核状态
 */
class XpSensitiveWord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xp_sensitive_word';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['badword'], 'required'],
            [['siteid', 'status'], 'integer'],
            [['badword'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'badword_id' => Yii::t('app', '敏感词id'),
            'badword' => Yii::t('app', '敏感词内容'),
            'siteid' => Yii::t('app', '站点id'),
            'status' => Yii::t('app', '审核状态'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return XpSensitiveWordQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpSensitiveWordQuery(get_called_class());
    }
}
