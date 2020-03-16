<?php
/**
 * This is the model class for table "XportalPushData";
 * @package backend\modules\xportal\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 20:55 */
namespace backend\modules\xportal\models;

use Yii;

/**
 * This is the model class for table "{{%xportal_push_data}}".
 *
 * @property int $push_id id
 * @property int $push_siteid 推荐站点id
 * @property int $push_news_id
 * @property string $push_year xml
 * @property string $push_month xml
 * @property string $push_papername
 * @property string $push_issue
 * @property int $push_date
 * @property string $push_pagename
 * @property string $push_title_eyebrow
 * @property string $push_title
 * @property string $push_title_sub
 * @property string $push_author
 * @property string $push_foreword
 * @property string $push_keywords
 * @property string $push_content
 * @property string $push_uploadfile 附件
 * @property string $push_resource
 * @property int $push_cms_category 栏目id
 * @property int $push_cms_siteid 站点id
 * @property string $push_islink 外部跳转链接
 * @property int $push_yes_no_islink 是否启用外部链接0为NO,1为YES
 * @property int $ifpass
 */
class XportalPushData extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xportal_push_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['push_siteid', 'push_news_id', 'push_date', 'push_cms_category', 'push_cms_siteid', 'push_yes_no_islink', 'ifpass'], 'integer'],
            [['push_content', 'push_uploadfile'], 'required'],
            [['push_content'], 'string'],
            [['push_year'], 'string', 'max' => 4],
            [['push_month'], 'string', 'max' => 2],
            [['push_papername', 'push_keywords'], 'string', 'max' => 50],
            [['push_issue'], 'string', 'max' => 10],
            [['push_pagename', 'push_author'], 'string', 'max' => 20],
            [['push_title_eyebrow', 'push_title', 'push_title_sub', 'push_resource'], 'string', 'max' => 100],
            [['push_foreword', 'push_uploadfile', 'push_islink'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'push_id' => Yii::t('app', 'id'),
            'push_siteid' => Yii::t('app', '推荐站点id'),
            'push_news_id' => Yii::t('app', 'Push News ID'),
            'push_year' => Yii::t('app', 'xml'),
            'push_month' => Yii::t('app', 'xml'),
            'push_papername' => Yii::t('app', 'Push Papername'),
            'push_issue' => Yii::t('app', 'Push Issue'),
            'push_date' => Yii::t('app', 'Push Date'),
            'push_pagename' => Yii::t('app', 'Push Pagename'),
            'push_title_eyebrow' => Yii::t('app', 'Push Title Eyebrow'),
            'push_title' => Yii::t('app', 'Push Title'),
            'push_title_sub' => Yii::t('app', 'Push Title Sub'),
            'push_author' => Yii::t('app', 'Push Author'),
            'push_foreword' => Yii::t('app', 'Push Foreword'),
            'push_keywords' => Yii::t('app', 'Push Keywords'),
            'push_content' => Yii::t('app', 'Push Content'),
            'push_uploadfile' => Yii::t('app', '附件'),
            'push_resource' => Yii::t('app', 'Push Resource'),
            'push_cms_category' => Yii::t('app', '栏目id'),
            'push_cms_siteid' => Yii::t('app', '站点id'),
            'push_islink' => Yii::t('app', '外部跳转链接'),
            'push_yes_no_islink' => Yii::t('app', '是否启用外部链接0为NO,1为YES'),
            'ifpass' => Yii::t('app', 'Ifpass'),
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
     * @return XportalPushDataQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XportalPushDataQuery(get_called_class());
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
