<?php

namespace common\models;

use common\utils\ToolUtil;
use Qcloud\Sms\SmsSingleSender;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%verify_code_history}}".
 *
 * @property string $id
 * @property string $mobile 手机号码
 * @property string $code 验证码
 * @property int $status 1未用   2已用
 * @property int $type 短信用途 1注册  2新建线索 3编辑线索
 * @property int $created_at
 * @property int $updated_at
 */
class VerifyCodeHistory extends \common\models\BaseModel
{
    //短信类型
    const CODE_TYPE_REGISTERED = 1;//注册
    const CODE_TYPE_NEW_CLUES = 2;//新建线索
    const CODE_TYPE_EDIT_CLUES = 3;//编辑线索
    const CODE_TYPE_PASSWORD = 4;//忘记密码
    const CODE_TYPE_LOGIN = 5;//登录
    const CODE_TYPE_UPDATE = 6;//修改密码
    const CODE_TYPE_BINDING = 7;//绑定手机号和更换手机号
    const CODE_TYPE_UNLOCK = 8;//解绑手机号
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%verify_code_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'type', 'created_at', 'updated_at'], 'integer'],
            [['mobile'], 'string', 'max' => 11],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => '手机号码',
            'code' => '验证码',
            'status' => '1未用   2已用',
            'type' => '短信用途 1注册  2新建线索 3编辑线索',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }


    /**
     * @Function 发送短信
     * @param $phoneNumbers 接收短信的手机号码
     * @param int $type     短信用途  1注册 2新建线索 3编辑线索 4修改手机号码  和verify_code_history表中的type对应
     * @Author Weihuaadmin@163.com
     * @return boolean
     */
    public static function sendSMS($phoneNumbers,$type = 1 )
    {
        $return = ToolUtil::returnMsg(false,'已经发送新手机号码,请注意查收!');
        $paramSMS = \Yii::$app->params['SMS'];
        $appId = $paramSMS['APPID'];
        $appKey = $paramSMS['APPKEY'];
        $smsSign = $paramSMS['SMSSIGN'];
        $tempId = $paramSMS['TEMP_ID'];
        $code = self::RandCode();
        try {
            $ssender = new SmsSingleSender($appId, $appKey);
//            $result = $ssender->send(0, "86", $phoneNumbers,$sendMsg, "", "");
            // $params = [$smsSign, $code, 5];
            $params = [$code, 5];

            $result = $ssender->sendWithParam("86", $phoneNumbers, $tempId,$params, $smsSign, "", "");
            $rsp = json_decode($result,true);
            if(($rsp['result'] == 0) && ($rsp['errmsg'] == 'OK')){
                \Yii::error("发送短信内容-{$smsSign}验证码：{$code}","sendSMSInfo");
                //插入数据库
                $insertData = [
                    'mobile' => (string)$phoneNumbers,
                    'code' => (string)$code,
                    'type' => $type,
                ];
                $verifyModel = new VerifyCodeHistory();
                $verifyModel -> setAttributes($insertData);
                $inserRes = $verifyModel ->save();
                if($inserRes){
                    $return = ToolUtil::returnMsg(true,'已经发送新手机号码,请注意查收!');
                    return $return;
                }
            }else{
                \Yii::error("发送短信失败：$result",'sendSMSError');
            }
            return $return;
        } catch(\Exception $e) {
            \Yii::error($e,'sendSMSError');
            return $return;
        }
    }

    /**
     * @Function 随机数字
     * @Author Weihuaadmin@163.com
     * @param int $len
     * @return string
     */
    public static function RandCode($len=4){
        $chars = str_repeat('0123456789', 3);
        // 位数过长重复字符串一定次数
        $chars = str_repeat($chars, $len);
        $chars = str_shuffle($chars);
        $code = substr($chars, 0, $len);
        return $code;
    }

    /**
     * @Function 验证短信
     * @Author Weihuaadmin@163.com
     * @param $phone 接收短信手机号码
     * @param $code 短信验证码
     * @param int $type 短信用途  1注册 2新建线索 3编辑线索 4修改手机号码  和verify_code_history表中的type对应
     */
    public static function verifySms($phone,$code,$type = 1){
        $return = ToolUtil::returnMsg(false,'验证码输入错误！');
        $dataCodeRes = self::find()->where(['mobile' => $phone, 'type' => $type, 'code' => $code])->orderBy(['id' => SORT_DESC])->asArray()->one();
        if($dataCodeRes){
            self::updateAll(['status' => 2]," mobile = :mobile AND type = :type",[":type" => $type, ':mobile' => $type]);
            $return = ToolUtil::returnMsg(true,'验证成功');
        }else{
            self::updateAll(['status' => 2]," mobile = :mobile AND type = :type",[":type" => $type, ':mobile' => $type]);
        }
        return $return;
    }

    /**
     * 验证手机号是否过期
     * @param $phone 接收短信手机号码
     * @param int $type 短信用途 1注册 4忘记密码 5登录 6修改密码
     */
    public static function timeoutIsSms($phone,$type = 1)
    {
        $getCode = self::find()->where(['mobile' => $phone, 'type' => $type])->orderBy(['id' => SORT_DESC])->asArray()->one();
        $created = $getCode['created_at'] + getVar('APPCODETIME',3);
        if($created > time()){
            return false;
        }
        return true;
    }
}
