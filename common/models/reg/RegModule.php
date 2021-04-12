<?php
/**
 * This is the model class for table "RegModule";
 * @package common\models\reg;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-04-05 17:45 */
namespace common\models\reg;

use Yii;

/**
 * This is the model class for table "{{%reg_module}}".
 *
 * @property int $id 主键ID
 * @property string $title 中文名
 * @property string $name 模块名或标识
 * @property string $title_initial 首字母简写
 * @property string $bootstrap 启用文件路径
 * @property string $service 服务调用类路径
 * @property string $cover 封面
 * @property string $brief_introduction 简单介绍
 * @property string $description 模块描述
 * @property string $author 作者
 * @property string $version 版本号
 * @property int $is_setting 设置
 * @property int $is_rule 是否要嵌入规则
 * @property int $is_merchant_route_map 路由映射
 * @property string $default_config 默认配置
 * @property string $console 控制台
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 */
class RegModule extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%reg_module}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_setting', 'is_rule', 'is_merchant_route_map', 'status', 'created_at', 'updated_at', 'created_id', 'updated_id'], 'integer'],
            [['default_config', 'console'], 'string'],
            [['title', 'version'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 100],
            [['title_initial'], 'string', 'max' => 50],
            [['bootstrap', 'service', 'cover'], 'string', 'max' => 200],
            [['brief_introduction'], 'string', 'max' => 140],
            [['description'], 'string', 'max' => 1000],
            [['author'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键ID'),
            'title' => Yii::t('app', '中文名'),
            'name' => Yii::t('app', '模块名或标识'),
            'title_initial' => Yii::t('app', '首字母简写'),
            'bootstrap' => Yii::t('app', '启用文件路径'),
            'service' => Yii::t('app', '服务调用类路径'),
            'cover' => Yii::t('app', '封面'),
            'brief_introduction' => Yii::t('app', '简单介绍'),
            'description' => Yii::t('app', '模块描述'),
            'author' => Yii::t('app', '作者'),
            'version' => Yii::t('app', '版本号'),
            'is_setting' => Yii::t('app', '设置'),
            'is_rule' => Yii::t('app', '是否要嵌入规则'),
            'is_merchant_route_map' => Yii::t('app', '路由映射'),
            'default_config' => Yii::t('app', '默认配置'),
            'console' => Yii::t('app', '控制台'),
            'status' => Yii::t('app', '状态[-1:删除;0:禁用;1启用]'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
            'created_id' => Yii::t('app', '添加者'),
            'updated_id' => Yii::t('app', '修改者'),
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
     * {@inheritdoc}
     * @return RegModuleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RegModuleQuery(get_called_class());
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
    /**
    * @var array 开关变量字段示例，如果已经开启，需要把字段赋值以数组形式列出
    */
   public $switchValues = [
       'status' => [
           'on' => 1,
           'off' => 0,
       ],
   //    'emphasis' => [
   //        'on' => 1,
   //        'off' => 0,
   //    ],
   //   //也可以是非 1，0 譬如，如下
   //   'isRecommend' => [
   //     'on' => 10,
   //     'off' => 0,
   //   ],
   ];
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
