<?php

namespace common\models\reg;

use Yii;

/**
 * This is the model class for table "{{%reg_widgets}}".
 *
 * @property int $id
 * @property string $title
 * @property string $name
 * @property string $title_initial
 * @property string|null $bootstrap
 * @property string|null $service
 * @property string|null $cover
 * @property string|null $brief_introduction
 * @property string|null $description
 * @property string|null $author
 * @property string|null $version
 * @property int|null $is_setting
 * @property int|null $is_rule
 * @property int|null $is_merchant_route_map
 * @property string|null $default_config
 * @property string|null $console
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_id
 * @property int|null $updated_id
 */
class RegWidgets extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%reg_widgets}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'name', 'title_initial'], 'required'],
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
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'name' => Yii::t('app', 'Name'),
            'title_initial' => Yii::t('app', 'Title Initial'),
            'bootstrap' => Yii::t('app', 'Bootstrap'),
            'service' => Yii::t('app', 'Service'),
            'cover' => Yii::t('app', 'Cover'),
            'brief_introduction' => Yii::t('app', 'Brief Introduction'),
            'description' => Yii::t('app', 'Description'),
            'author' => Yii::t('app', 'Author'),
            'version' => Yii::t('app', 'Version'),
            'is_setting' => Yii::t('app', 'Is Setting'),
            'is_rule' => Yii::t('app', 'Is Rule'),
            'is_merchant_route_map' => Yii::t('app', 'Is Merchant Route Map'),
            'default_config' => Yii::t('app', 'Default Config'),
            'console' => Yii::t('app', 'Console'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_id' => Yii::t('app', 'Created ID'),
            'updated_id' => Yii::t('app', 'Updated ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return RegWidgetsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RegWidgetsQuery(get_called_class());
    }
}
