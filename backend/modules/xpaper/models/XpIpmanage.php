<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_ipmanage".
 *
 * @property int $id
 * @property string $ip 被限制的ip
 * @property int $siteid 站点id
 * @property int $status 状态
 */
class XpIpmanage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xp_ipmanage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip'], 'required'],
            [['siteid', 'status'], 'integer'],
            [['ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ip' => Yii::t('app', '被限制的ip'),
            'siteid' => Yii::t('app', '站点id'),
            'status' => Yii::t('app', '状态'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return XpIpmanageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpIpmanageQuery(get_called_class());
    }
}
