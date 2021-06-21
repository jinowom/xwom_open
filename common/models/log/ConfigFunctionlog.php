<?php

namespace common\models\log;

use Yii;

/**
 * This is the model class for table "config_functionlog".
 *
 * @property int $id
 * @property string $app_id 应用id
 * @property int $merchant_id 商户id
 * @property int $user_id 用户id
 * @property string $method 提交类型：1:正常登录；2 登录异常（密码出错超出次数）；3 Ip地址异常；4-非法链接访问 5.页面浏览 6.查询数据 7.增加数据 8.修改数据 9.删除数据
 * @property string $module 模块
 * @property string $controller 控制器
 * @property string $action 方法
 * @property string $url 提交url
 * @property string $get_data get数据
 * @property string $post_data post数据
 * @property string $header_data header数据
 * @property string $ip ip地址
 * @property int $error_code 报错code
 * @property string $error_msg 报错信息
 * @property string $error_data 报错日志
 * @property string $req_id 对外id
 * @property string $user_agent UA信息
 * @property string $device 设备信息
 * @property string $device_uuid 设备唯一号
 * @property string $device_version 设备版本
 * @property string $device_app_version 设备app版本
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $siteid 站点id
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 * @property int $updated_at 修改时间
 * @property int $created_at 添加时间
 * @property int $is_del 是否删除 0否 1是
 */
class ConfigFunctionlog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_functionlog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'user_id', 'error_code', 'status', 'siteid', 'created_id', 'updated_id', 'updated_at', 'created_at', 'is_del'], 'integer'],
            [['get_data', 'post_data', 'header_data', 'error_data'], 'string'],
            [['app_id', 'module', 'action', 'req_id', 'device_uuid'], 'string', 'max' => 50],
            [['method', 'device_version', 'device_app_version'], 'string', 'max' => 20],
            [['controller'], 'string', 'max' => 100],
            [['url', 'error_msg'], 'string', 'max' => 1000],
            [['ip'], 'string', 'max' => 16],
            [['user_agent'], 'string', 'max' => 200],
            [['device'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'merchant_id' => 'Merchant ID',
            'user_id' => 'User ID',
            'method' => 'Method',
            'module' => 'Module',
            'controller' => 'Controller',
            'action' => 'Action',
            'url' => 'Url',
            'get_data' => 'Get Data',
            'post_data' => 'Post Data',
            'header_data' => 'Header Data',
            'ip' => 'Ip',
            'error_code' => 'Error Code',
            'error_msg' => 'Error Msg',
            'error_data' => 'Error Data',
            'req_id' => 'Req ID',
            'user_agent' => 'User Agent',
            'device' => 'Device',
            'device_uuid' => 'Device Uuid',
            'device_version' => 'Device Version',
            'device_app_version' => 'Device App Version',
            'status' => 'Status',
            'siteid' => 'Siteid',
            'created_id' => 'Created ID',
            'updated_id' => 'Updated ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'is_del' => 'Is Del',
        ];
    }
}
