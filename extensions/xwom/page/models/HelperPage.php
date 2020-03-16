<?php

namespace yigou\page\models;

use Yii;

/**
 * This is the model class for table "{{%helper_page}}".
 *
 * @property string $content
 * @property string $url_key
 * @property string $sort
 */
class HelperPage extends \kartik\tree\models\Tree
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%helper_page}}';
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
                [['content'], 'string'],
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
                'content' => Yii::t('yigou_page', 'Content'),
                'url_key' => Yii::t('yigou_page', 'Url Key'),
                'sort' => Yii::t('yigou_page', 'Sort'),
            ]
        );
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
