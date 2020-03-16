<?php

namespace common\models\third;

use Yii;

/**
 * This is the model class for table "{{%third_interface_key}}".
 *
 * @property int $id
 * @property string $name 接口说明
 * @property string $clientKey 申请的appkey
 * @property string $clientSecret 申请的appsecret
 * @property string $callBackUrl 回调地址
 * @property string $type 1:微博  2:抖音 3:微信 4：头条
 * @property int $unitId 单位id
 * @property string $is_del 是否删除 
 * @property int $created_at 录入时间
 * @property int $updated_at 修改时间
 */
class ThirdInterfaceKey extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%third_interface_key}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clientKey', 'clientSecret', 'callBackUrl', 'type', 'unitId', 'is_del', 'created_at', 'updated_at'], 'required'],
            [['type', 'is_del'], 'string'],
            [['unitId', 'created_at', 'updated_at'], 'integer'],
            [['name', 'clientSecret'], 'string', 'max' => 50],
            [['clientKey'], 'string', 'max' => 30],
            [['callBackUrl'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '接口说明',
            'clientKey' => '申请的appkey',
            'clientSecret' => '申请的appsecret',
            'callBackUrl' => '回调地址',
            'type' => '1:微博  2:抖音 3:微信 4：头条',
            'unitId' => '单位id',
            'is_del' => '是否删除
',
            'created_at' => '录入时间',
            'updated_at' => '修改时间',
        ];
    }
}
