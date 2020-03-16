<?php
/**
 * This is the model class for table "XportalCategory";
 * @package backend\modules\xportal\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 17:15 */
namespace backend\modules\xportal\models;

use Yii;

/**
 * This is the model class for table "{{%xportal_category}}".
 *
 * @property int $catid 栏目id
 * @property string $catname 栏目中文名称
 * @property string $letter 栏目英文名称
 * @property string $alias 栏目别名
 * @property int $module 所属模板类型
 * @property int $category_theme 栏目模板
 * @property int $temparticle 栏目下内容模板
 * @property int $listorder 栏目排序
 * @property int $type 类别
 * @property int $category_type 栏目类型关联cms_c_c_c表id
 * @property string $description 栏目描述
 * @property string $bank_url 栏目跳转url
 * @property string $parentdir 父目录
 * @property string $catdir 目录
 * @property string $url 链接地址
 * @property int $items 栏目数量
 * @property int $hits 栏目点击数
 * @property string $setting 相关配置信息
 * @property int $ismenu 栏目显示与隐藏1隐藏
 * @property int $parameter 导航附属参数
 * @property string $pic 栏目小图标
 * @property int $sethtml 生成静态1静态
 * @property int $corank 浏览权限
 * @property int $siteid 站点id
 * @property int $cache 缓存依赖
 * @property int $app_category_theme 手机端栏目模板ID
 * @property int $app_content_theme 手机端内容模板ID
 *
 * @property XportalCCC $categoryType
 * @property XportalTheme $categoryTheme
 * @property XportalTheme $temparticle0
 * @property XportalThemeBigType $module0
 */
class XportalCategory extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xportal_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alias', 'module', 'category_theme', 'temparticle', 'category_type', 'description', 'bank_url', 'setting'], 'required'],
            [['module', 'category_theme', 'temparticle', 'listorder', 'type', 'category_type', 'items', 'hits', 'ismenu', 'parameter', 'sethtml', 'corank', 'siteid', 'cache', 'app_category_theme', 'app_content_theme'], 'integer'],
            [['description', 'setting'], 'string'],
            [['catname', 'letter', 'parentdir', 'catdir', 'url', 'pic'], 'string', 'max' => 255],
            [['alias', 'bank_url'], 'string', 'max' => 300],
            [['category_type'], 'exist', 'skipOnError' => true, 'targetClass' => XportalCCC::className(), 'targetAttribute' => ['category_type' => 'id']],
            [['category_theme'], 'exist', 'skipOnError' => true, 'targetClass' => XportalTheme::className(), 'targetAttribute' => ['category_theme' => 'theme_id']],
            [['temparticle'], 'exist', 'skipOnError' => true, 'targetClass' => XportalTheme::className(), 'targetAttribute' => ['temparticle' => 'theme_id']],
            [['module'], 'exist', 'skipOnError' => true, 'targetClass' => XportalThemeBigType::className(), 'targetAttribute' => ['module' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'catid' => Yii::t('app', '栏目id'),
            'catname' => Yii::t('app', '栏目中文名称'),
            'letter' => Yii::t('app', '栏目英文名称'),
            'alias' => Yii::t('app', '栏目别名'),
            'module' => Yii::t('app', '所属模板类型'),
            'category_theme' => Yii::t('app', '栏目模板'),
            'temparticle' => Yii::t('app', '栏目下内容模板'),
            'listorder' => Yii::t('app', '栏目排序'),
            'type' => Yii::t('app', '类别'),
            'category_type' => Yii::t('app', '栏目类型关联cms_c_c_c表id'),
            'description' => Yii::t('app', '栏目描述'),
            'bank_url' => Yii::t('app', '栏目跳转url'),
            'parentdir' => Yii::t('app', '父目录'),
            'catdir' => Yii::t('app', '目录'),
            'url' => Yii::t('app', '链接地址'),
            'items' => Yii::t('app', '栏目数量'),
            'hits' => Yii::t('app', '栏目点击数'),
            'setting' => Yii::t('app', '相关配置信息'),
            'ismenu' => Yii::t('app', '栏目显示与隐藏1隐藏'),
            'parameter' => Yii::t('app', '导航附属参数'),
            'pic' => Yii::t('app', '栏目小图标'),
            'sethtml' => Yii::t('app', '生成静态1静态'),
            'corank' => Yii::t('app', '浏览权限'),
            'siteid' => Yii::t('app', '站点id'),
            'cache' => Yii::t('app', '缓存依赖'),
            'app_category_theme' => Yii::t('app', '手机端栏目模板ID'),
            'app_content_theme' => Yii::t('app', '手机端内容模板ID'),
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
    public function getCategoryType()
    {
        return $this->hasOne(XportalCCC::className(), ['id' => 'category_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryTheme()
    {
        return $this->hasOne(XportalTheme::className(), ['theme_id' => 'category_theme']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemparticle0()
    {
        return $this->hasOne(XportalTheme::className(), ['theme_id' => 'temparticle']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModule0()
    {
        return $this->hasOne(XportalThemeBigType::className(), ['id' => 'module']);
    }

    /**
     * {@inheritdoc}
     * @return XportalCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XportalCategoryQuery(get_called_class());
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
