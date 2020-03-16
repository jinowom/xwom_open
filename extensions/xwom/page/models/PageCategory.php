<?php

namespace yigou\page\models;

use Yii;

/**
 * This is the model class for table "{{%page_category}}".
 *
 * @property string $url_key
 * @property string $sort
 * @property Page[] $pages
 */
class PageCategory extends \kartik\tree\models\Tree
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_category}}';
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return parent::find()->orderBy('sort');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['sort'], 'default', 'value' => 0],
                [['url_key'], 'required'],
                [['url_key'], 'string', 'max' => 255],
                [['sort'], 'integer'],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'url_key' => Yii::t('yigou_page', 'Url Key'),
                'sort' => Yii::t('yigou_page', 'Sort'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['category_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->updateAll(['sort' => $this->sort], ['root' => $this->root]);
        return parent::beforeSave($insert);
    }
}
