<?php
/**
 * This is the model class for table "XportalChannel";
 * @package backend\modules\xportal\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 17:01 */
namespace backend\modules\xportal\models;

use Yii;

/**
 * This is the model class for table "{{%xportal_channel}}".
 *
 * @property int $channel_id 频道id
 * @property string $channel_ch_name 频道中文名称
 * @property string $channel_en_name 频道英文名称
 * @property string $channel_alias 频道别名
 * @property int $channel_listorder 频道排序
 * @property int $channel_theme_type 频道类型关联cms_theme_big_type表id
 * @property int $channel_type 频道类型关联cms_c_c_c表id
 * @property int $channel_theme_id 关联模板表id
 * @property string $channel_description 频道描述
 * @property string $bank_url 频道跳转url
 * @property int $ismenu 频道显示与隐藏1隐藏
 * @property int $index_ismenu 首页显示与隐藏1隐藏
 * @property int $parameter 导航附属参数
 * @property int $cache 缓存依赖
 * @property string $pic 终端频道小图标
 * @property string $app_ismenu 移动终端是否公开
 * @property string $default_subscribe_channel 是否默认订阅y是，n不是
 * @property int $app_sort 终端频道排序
 * @property int $channel_top 默认订阅频道是否置顶，1是，0不是
 * @property int $siteid 站点id
 * @property int $app_channel_theme 手机端频道模板ID
 *
 * @property XportalThemeBigType $channelThemeType
 * @property XportalCCC $channelType
 * @property XportalTheme $channelTheme
 */
class XportalChannel extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xportal_channel}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['channel_alias', 'channel_description', 'bank_url'], 'required'],
            [['channel_listorder', 'channel_theme_type', 'channel_type', 'channel_theme_id', 'ismenu', 'index_ismenu', 'parameter', 'cache', 'app_sort', 'channel_top', 'siteid', 'app_channel_theme'], 'integer'],
            [['channel_description', 'app_ismenu', 'default_subscribe_channel'], 'string'],
            [['channel_ch_name'], 'string', 'max' => 30],
            [['channel_en_name', 'channel_alias', 'bank_url'], 'string', 'max' => 300],
            [['pic'], 'string', 'max' => 255],
            [['channel_theme_type'], 'exist', 'skipOnError' => true, 'targetClass' => XportalThemeBigType::className(), 'targetAttribute' => ['channel_theme_type' => 'id']],
            [['channel_type'], 'exist', 'skipOnError' => true, 'targetClass' => XportalCCC::className(), 'targetAttribute' => ['channel_type' => 'id']],
            [['channel_theme_id'], 'exist', 'skipOnError' => true, 'targetClass' => XportalTheme::className(), 'targetAttribute' => ['channel_theme_id' => 'theme_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'channel_id' => Yii::t('app', '频道id'),
            'channel_ch_name' => Yii::t('app', '频道中文名称'),
            'channel_en_name' => Yii::t('app', '频道英文名称'),
            'channel_alias' => Yii::t('app', '频道别名'),
            'channel_listorder' => Yii::t('app', '频道排序'),
            'channel_theme_type' => Yii::t('app', '频道类型关联cms_theme_big_type表id'),
            'channel_type' => Yii::t('app', '频道类型关联cms_c_c_c表id'),
            'channel_theme_id' => Yii::t('app', '关联模板表id'),
            'channel_description' => Yii::t('app', '频道描述'),
            'bank_url' => Yii::t('app', '频道跳转url'),
            'ismenu' => Yii::t('app', '频道显示与隐藏1隐藏'),
            'index_ismenu' => Yii::t('app', '首页显示与隐藏1隐藏'),
            'parameter' => Yii::t('app', '导航附属参数'),
            'cache' => Yii::t('app', '缓存依赖'),
            'pic' => Yii::t('app', '终端频道小图标'),
            'app_ismenu' => Yii::t('app', '移动终端是否公开'),
            'default_subscribe_channel' => Yii::t('app', '是否默认订阅y是，n不是'),
            'app_sort' => Yii::t('app', '终端频道排序'),
            'channel_top' => Yii::t('app', '默认订阅频道是否置顶，1是，0不是'),
            'siteid' => Yii::t('app', '站点id'),
            'app_channel_theme' => Yii::t('app', '手机端频道模板ID'),
        ];
    }
    
    /**
    *验证之前的处理.如果不需要，调试后请删除
    */
    /*
    public function beforeValidate()
    {
        if (!empty($this->start_at) && strpos($this->start_at, '-')) {
            $this->start_at = strtotime($this->start_at);
        }
        if (!empty($this->end_at) && strpos($this->end_at, '-')) {
            $this->end_at = strtotime($this->end_at);
        }

        return parent::beforeValidate();
    }
    */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannelThemeType()
    {
        return $this->hasOne(XportalThemeBigType::className(), ['id' => 'channel_theme_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannelType()
    {
        return $this->hasOne(XportalCCC::className(), ['id' => 'channel_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannelTheme()
    {
        return $this->hasOne(XportalTheme::className(), ['theme_id' => 'channel_theme_id']);
    }

    /**
     * {@inheritdoc}
     * @return XportalChannelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XportalChannelQuery(get_called_class());
    }

    /**
     * beforeSave 存储数据库之前事件的实务的编排、注入；
     */ 
    public function beforeSave($insert)
    {
    	if(parent::beforeSave($insert))
    	{
                $admin_id = Yii::$app->user->getId();
    		if($insert)
    		{
                    $this->created_at = time();
                    $this->updated_at = time();
                    if($admin_id){
                       $this->created_id = $admin_id; 
                       $this->updated_id = $admin_id; 
                    }	
    		}
    		else 
    		{
                    $this->updated_at = time();
                    $this->updated_id= $admin_id;
    		}
    		return true;			
    	}
    	else 
    	{
    		return false;
    	}
    }
    /*
    * afterSave 保存之后的事件  示例
    */
//    public function afterSave($insert, $changedAttributes)
//    {
//        parent::afterSave($insert, $changedAttributes);
//        if ($insert) {
//            // 插入新数据之后修改订单状态
//            Order::updateAll(['shipping_status' => Order::SHIPPING_STATUS1, 'shipping_at' => time()], ['trade_no' => $this->order_trade_no]);
//        }
//    }
    
    /*
    * beforeDelete 删除之前事件 编排 ；  afterDelete 删除之后的事件编排  示例
    */
    
//    public function beforeDelete()
//    {
//        parent::beforeDelete();
//        
//    }
    /**
    * 保证数据事务完成，否则回滚
    */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE
            // self::SCENARIO_DEFAULT => self::OP_INSERT
        ];
    }
}
