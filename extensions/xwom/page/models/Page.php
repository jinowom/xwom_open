<?php

namespace yigou\page\models;

use kiwi\Kiwi;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $page_id
 * @property string $title
 * @property string $html
 * @property integer $sort
 * @property string $url_key
 * @property integer $category_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property PageCategory $pageCategory
 */
class Page extends \kiwi\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort'], 'default', 'value' => 0],
            [['title', 'url_key', 'category_id'], 'required'],
            [['html'], 'string'],
            [['sort', 'category_id'], 'integer'],
            [['title', 'url_key'], 'string', 'max' => 255],
            ['category_id', 'exist', 'targetClass' => Kiwi::getPageCategoryClass(), 'targetAttribute' => 'id']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => Yii::t('yigou_page', 'Page ID'),
            'title' => Yii::t('yigou_page', 'Title'),
            'html' => Yii::t('yigou_page', 'Html'),
            'sort' => Yii::t('yigou_page', 'Sort'),
            'url_key' => Yii::t('yigou_page', 'Url Key'),
            'category_id' => Yii::t('yigou_page', 'Category ID'),
            'created_at' => Yii::t('yigou_page', 'Created At'),
            'created_by' => Yii::t('yigou_page', 'Created By'),
            'updated_at' => Yii::t('yigou_page', 'Updated At'),
            'updated_by' => Yii::t('yigou_page', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'Timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
            'Blameable' => [
                'class' => BlameableBehavior::className(),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageCategory()
    {
        return $this->hasOne(PageCategory::className(), ['id' => 'category_id']);
    }
}
