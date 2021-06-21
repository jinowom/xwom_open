<?php
/**
 * This is the model class for table "XpXmlFile";
 * @package common\models\xp;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-07-12 15:36 */
namespace common\models\xp;

use Yii;

/**
 * This is the model class for table "{{%xp_xml_file}}".
 *
 * @property int $id ID
 * @property string $file_name 文件名
 * @property string $path 路径
 * @property int $status 状态[0:待入库;1已入库]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 * @property int $site_id 站点id
 * @property int $is_del 是否删除 0否 1是
 * @property int $sortOrder 排序
 */
class XpXmlFile extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xp_xml_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'status', 'created_at', 'updated_at', 'created_id', 'updated_id', 'site_id', 'is_del', 'sortOrder'], 'integer'],
            [['file_name', 'path'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'file_name' => Yii::t('app', '文件名'),
            'path' => Yii::t('app', '路径'),
            'status' => Yii::t('app', '状态[0:待入库;1已入库]'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
            'created_id' => Yii::t('app', '添加者'),
            'updated_id' => Yii::t('app', '修改者'),
            'site_id' => Yii::t('app', '站点id'),
            'is_del' => Yii::t('app', '是否删除 0否 1是'),
            'sortOrder' => Yii::t('app', '排序'),
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
     * @return XpXmlFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpXmlFileQuery(get_called_class());
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
   //public $switchValues = [
   //    'status' => [
   //        'on' => 1,
   //        'off' => 0,
   //    ],
   //    'emphasis' => [
   //        'on' => 1,
   //        'off' => 0,
   //    ],
   //   //也可以是非 1，0 譬如，如下
   //   'isRecommend' => [
   //     'on' => 10,
   //     'off' => 0,
   //   ],
   //];
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
