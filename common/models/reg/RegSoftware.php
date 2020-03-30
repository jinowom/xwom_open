<?php

namespace common\models\reg;

use Yii;

/**
 * This is the model class for table "{{%reg_software}}".
 *
 * @property int $id 主键ID
 * @property string $title 中文名
 * @property string $name 应用名或标识
 * @property string $title_initial 首字母简写
 * @property string|null $bootstrap 启用文件路径
 * @property string|null $service 服务调用类路径
 * @property string|null $cover 封面
 * @property string|null $brief_introduction 简单介绍
 * @property string|null $description 应用描述
 * @property string|null $author 作者
 * @property string|null $version 版本号
 * @property int|null $is_setting 设置
 * @property int|null $is_rule 是否要嵌入规则
 * @property string|null $parent_rule_name 父级路由权限标识
 * @property string|null $route_map 路由映射标识
 * @property string|null $default_config 默认配置
 * @property string|null $console 控制台
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 * @property int|null $created_id 添加者
 * @property int|null $updated_id 修改者
 * @property int|null $sortOrder 排序
 */
class RegSoftware extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%reg_software}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_setting', 'is_rule', 'status', 'created_at', 'updated_at', 'created_id', 'updated_id', 'sortOrder'], 'integer'],
            [['default_config', 'console'], 'string'],
            [['title', 'author'], 'string', 'max' => 80],
            [['name', 'route_map'], 'string', 'max' => 100],
            [['title_initial'], 'string', 'max' => 50],
            [['bootstrap', 'service', 'cover'], 'string', 'max' => 200],
            [['brief_introduction'], 'string', 'max' => 140],
            [['description'], 'string', 'max' => 1000],
            [['version'], 'string', 'max' => 20],
            [['parent_rule_name'], 'string', 'max' => 255],
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
            'name' => Yii::t('app', '应用名或标识'),
            'title_initial' => Yii::t('app', '首字母简写'),
            'bootstrap' => Yii::t('app', '启用文件路径'),
            'service' => Yii::t('app', '服务调用类路径'),
            'cover' => Yii::t('app', '封面'),
            'brief_introduction' => Yii::t('app', '简单介绍'),
            'description' => Yii::t('app', '应用描述'),
            'author' => Yii::t('app', '作者'),
            'version' => Yii::t('app', '版本号'),
            'is_setting' => Yii::t('app', '设置'),
            'is_rule' => Yii::t('app', '是否要嵌入规则'),
            'parent_rule_name' => Yii::t('app', '父级路由权限标识'),
            'route_map' => Yii::t('app', '路由映射标识'),
            'default_config' => Yii::t('app', '默认配置'),
            'console' => Yii::t('app', '控制台'),
            'status' => Yii::t('app', '状态[-1:删除;0:禁用;1启用]'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
            'created_id' => Yii::t('app', '添加者'),
            'updated_id' => Yii::t('app', '修改者'),
            'sortOrder' => Yii::t('app', '排序'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return RegSoftwareQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RegSoftwareQuery(get_called_class());
    }
}
