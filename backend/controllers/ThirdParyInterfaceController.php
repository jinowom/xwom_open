<?php
/**
 * Created by PhpStorm.
 * @Author 1042463605@qq.com
 * Date: 2019/10/12
 * Time: 14:05
 */

namespace backend\controllers;


use common\models\AdminUnit;
use common\models\third\ThirdInterfaceKey;
use common\utils\ToolUtil;

class ThirdParyInterfaceController extends BaseController
{
    protected $except = ['index','login','third-pary-interface/interface-edit'];
    //protected $ClientKey = 'awyg5urq2p0jw73a';
    //protected $ClientSecret = 'ee3e8bff1d9bcda7c35d6c619ac8bdb7';
    /**
     * @Function
     * @Author 1042463605@qq.com
     * @return string
     */
    public function actionIndex(){
        $isSuper = self::IsSuperAdmin($this->user_id);
        $interfaceName = $this->get('interfaceName');
        return $this->render('interfacelist',['interfaceName'=>$interfaceName,'isSuper'=>$isSuper]);
    }
    /**
     * @Function 获取单位管理数据
     * @Author 1042463605@qq.com
     */
    public function actionGetInterfaceList(){
        $interfaceName = $this->post('interfaceName');
        //$isSuper = self::IsSuperAdmin($this->user_id);
        //if($isSuper){
            if($interfaceName !=''){
                $query = ThirdInterfaceKey::find()->filterWhere(['AND',['is_del' => 0]])->andFilterWhere(['like','name',$interfaceName]);
            }else{
                $query = ThirdInterfaceKey::find()->filterWhere(['AND',['is_del' => 0]]);
            }
        //}else{
         //   echo '根据所在的读取';exit;
        //}

        $this->sidx = 'created_at';
        $dealFunction = function ($lists){
            foreach ($lists as $key => $list){
                $lists[$key]['created_at'] = ToolUtil::getDate($list['created_at'],"Y-m-d H:i:s");
                $lists[$key]['updated_at'] = ToolUtil::getDate($list['updated_at'],"Y-m-d H:i:s");
            }
            return $lists;
        };
        return $this->getJqTableData($query,$dealFunction);
    }
    /**
     * @Function 修改接口参数信息
     * @Author 1042463605@qq.com
     * @return []
     */
    public function actionInterfaceEdit(){
        if(\Yii::$app->request->isGet){
            return $this->render('_interfaceadd',['interfaceInfo' =>[]]);
            exit;
        }
        $postData = $this->post();
        if($postData){
            if(isset($postData['interface']['interfaceid']) && $postData['interface']['interfaceid'] ==''){
                $interface = new  ThirdInterfaceKey();
                $interface->name = $postData['interface']['name'];
                $interface->unitId = $postData['interface']['unitId'];
                $interface->clientKey = $postData['interface']['clientKey'];
                $interface->clientSecret = $postData['interface']['clientSecret'];
                $interface->callBackUrl = $postData['interface']['callBackUrl'];
                $interface->type = $postData['interface']['type'];
                $interface->created_at = time();
                $interface->updated_at = time();
                $interface->is_del = 0;
                ///var_dump($interface->save());exit;
                $query = $interface->save();
                if($query){
                    $return = ToolUtil::returnAjaxMsg(true,'新增成功');
                }else{
                    $return = ToolUtil::returnAjaxMsg(true,'操纵失败');
                }
                //$query->createCommand()->getRawSql();
            }else{
                $Res = ThirdInterfaceKey::updateAll(
                    ['name' => $postData['interface']['name'],
                        'unitId' => $postData['interface']['unitId'],
                            'clientKey' => $postData['interface']['clientKey'],
                                'clientSecret' => $postData['interface']['clientSecret'],
                                    'callBackUrl' => $postData['interface']['callBackUrl'],
                                        'type' => $postData['interface']['type'],
                    'updated_at'=>time()]," id = :id",[":id" => postData['interface']['interfaceid']]);
                if($Res){
                    $return = ToolUtil::returnAjaxMsg(true,'编辑成功');
                }else{
                    $return = ToolUtil::returnAjaxMsg(false,'操作失败');
                }
            }
        }else{
            $return = ToolUtil::returnAjaxMsg(false,'操作失败');
        }
        return $this->asJson($return);
    }
    /**
     * @Function
     * @Author 1042463605@qq.com
     * @return string
     */
    public function actionList(){
        return $this->render('list');
        //$callBackUrl = 'https://www.mlwch.com/custom/douYinCallback.html';
        //$url         = 'https://open.douyin.com/platform/oauth/connect/?client_key=' . $this->ClientKey . '&response_type=code&scope=user_info&redirect_uri=' . $callBackUrl . '&state=spacing';
        // header('Location:' . $url);
        // exit;
    }
    public function actionLogin(){
        return $this->render('login');
    }

    //抖音回调地址
    public function actionDouYinCallback()
    {
        //$res = file_get_contents('php://input');
        $code = Yii::$app->request->get('code');
        if($code){
            $url          = 'https://open.douyin.com/oauth/access_token/?client_key=' . $this->ClientKey . '&client_secret=' . $this->ClientSecret . '&code=' . $code . '&grant_type=authorization_code';
            $res          = json_decode(http_get($url), true);
            $openId       = $res['data']['open_id'];
            $access_token = $res['data']['access_token'];
            $url          = 'https://open.douyin.com/oauth/userinfo/?access_token=' . $access_token . '&open_id=' . $openId;
            $userInfo     = json_decode(http_get($url), true);
            if ($userInfo['message'] == 'success') {
                echo '<br/>昵称：' . $userInfo['data']['nickname'];
                echo '<br/>头像：<img src="' . $userInfo['data']['avatar'] . '" height="100"/>';
                echo '<br/>open_id：' . $userInfo['data']['open_id'];
                echo '<br/>union_id：' . $userInfo['data']['union_id'];
            }
            //开始上传视频
            //header( "Content-type:video/mp4");
            $PSize = filesize('/data/wwwroot/default/upload/test/003UWSaDlx07x4ZX44Ra01041201KtEq0E010.mp4');
            $videodata = fread(fopen('/data/wwwroot/default/upload/test/003UWSaDlx07x4ZX44Ra01041201KtEq0E010.mp4', "r"), $PSize);
            $postUrl = 'https://open.douyin.com//video/upload/';
            $res = https_post($postUrl,array('open_id'=>$openId,'access_token'=>$access_token,'video'=>$videodata));
            print_r($res);exit;
        }

    }
    //微博调用接口回调地址
    public function actionWeiboCallBack()
    {
        if (isset($_REQUEST['code'])) {
            $keys                 = array();
            $keys['code']         = $_REQUEST['code'];
            $keys['redirect_uri'] = $this->weiboCallback;
            try {
                $this->weiboToken = $this->weibo->getAccessToken('code', $keys);
            } catch (OAuthException $e) {
            }
        }
        if ($this->weiboToken) {
            //$_SESSION['token'] = $token;
            $this->redis->mset($this->weiboToken);
            setcookie('weibojs_' . $this->weibo->client_id, http_build_query($this->weiboToken));
            //echo '授权完成,<a href="/custom/weiboList.html">进入你的微博列表页面</a><br />';
            header('Location:https://www.mlwch.com/custom/weiboList.html');
        } else {
            echo '授权失败。';exit;
        }
    }
    public function actionWeiboList()
    {
        //$list         = $c->home_timeline();
        $uid_get      = $this->client->get_uid();
        $uid          = $uid_get['uid'];
        $user_message = $this->client->show_user_by_id($uid);
    }
    //微博调用接口取消授权回调地址
    public function actionSendWeibo()
    {
        $text = urldecode(Yii::$app->request->get('text'));
        if ($text) {
            $ret = $this->client->share($text . $this->mainTarget); //发送微博
            if (isset($ret['error_code']) && $ret['error_code'] > 0) {
                echo "<p>发送失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
            } else {
                echo json_encode(array('status' => '200'));
            }
        }
    }
    public function actionPublish()
    {
        if ($this->weiboToken) {
            $params = array(
                'title'        => "木兰围场——我可爱的家乡",
                'content'      => rawurlencode("木兰围场——我可爱的家乡，位于河北省东北部，总面积9212平方公里，是河北省最大的县。<br/>西北、北、东分别与内蒙古族自治区多伦县、克什克腾旗、赤峰市为邻，西与丰宁满族自治县相连，南与隆化县接壤。是华北地区通往内蒙古和燕北地区的要道。
<br/>全县人口50万人，人口密度为每平方公里55人，少数民族人口达25.51万人，其中满族人口最多为20.2万人。这里曾作为清朝皇帝“岁举秋狝大典”的皇家猎苑而闻名中外。<br/>
木兰围场这块清皇朝猎苑，她与承德避暑山庄融为一体，一年四季节气分明，气候宜人，自古以来就是一处水草丰沛、禽兽繁居的天然名苑。群山分层，众壑朝宗，坝下山地沟谷纵横，坝上草原漫岗迂回，湖泊星罗，河流蜿蜒，绿草如茵，鲜花如潮。郁郁葱葱的塞罕坝、御道口牧场、广袤的原始森林和人工林覆盖无边的原野，被人们称为“塞上明珠”、“北方的金三角”，1992年又被国家林业部命名为国家级森林公园。这里巧夺天工的美景，构成了一幅美丽的画卷。这里的一切都充满了神奇的魅力，来此必将圆你回归自然之梦。这里早已成为旅游、度假、观光的最好场所，也是文人墨客施展艺术才华的最理想胜地。被誉为“水的源头、云的故乡、花的世界、林的海洋、珍禽异兽的天堂”。如今已成为京北黄金旅游线上一颗璀璨的明珠。
<br/>木兰围场不仅“山川形胜、甲于紫塞”，而且拥有极为丰富的自然资源。这里是“中国马铃薯之乡”、全国马铃薯种薯基地县、国家畜牧业发展重点县、河北省用材林基地县，非金属矿产资源闻名全国，独特的山野资源享誉海外。
<br/>木兰围场历史悠久。史前遗址、燕秦长城、元代白塔等不胜枚举。及至清朝更有一段辉煌的历史在这里驻足。自康熙二十年(1681年)设立木兰围场至道光四年（1825年）废止，140余年间，在这块神奇美丽的土地上发生了许多重大历史事件，留下了众多的文物古迹，也孕育了丰富独特的满蒙文化，它是一个王朝历史兴衰的见证和缩影，是弘扬民族文化、发展民族经济、增强民族向心力和凝聚力的实证史料，具有较高的史学价值。木兰围场还有许多现代文物和著名的爱国主义教育基地，革命先烈用热血和生命换来的新生，50万木兰儿女在县委、县政府的领导下团结一心，艰苦创业，唱响了改天换地的壮歌，重铸了木兰围场的辉煌。
悠久的历史不仅留下了一座座文明古迹，还流传出一段段美丽传说，给这个清王朝猎苑不免增添了几分神秘的色彩。
<br/>在伊逊河东岸，有一座大山叫凤凰岭，这里盛产高质量硅砂，无论怎么开采，也永远开采不完。
<br/>传说孙悟空大闹天宫时，用金箍棒打坏了天宫的一段八宝玲珑琉璃瓦。事后，为修补它，天庭派了许多能工巧匠四处找材料，走遍五湖四海，八山九岭，也没有找到这称心如意的材料。
一天，他们来到燕北地段的松州地面，忽见有一道岗子，五光十色，他们有点奇怪，走近一看，真是“踏破铁鞋无觅处，得来全不费功夫”，这正是他们要找的晶莹剔透的元砂。可是松州真君不肯给，于是商定了一个君子协定，修好了琉璃瓦后，每年春秋两季加倍奉还。这样，天宫中破损的八宝琉璃瓦才得以修复。直到今天，往银河里望去，有一段特别明亮的地方，就是用这里所取的元砂材料修复的琉璃瓦放出的光芒。
从此以后，每到春秋两季，风神就把全国各地的晶莹细砂吹落在木兰围场，以兑现“奉还”的诺言。不知过了多少年，这里便形成了一道望不到边际的硅砂岭，因其以“奉还”之约而成，故叫“奉还岭”，后因“奉还”与“凤凰”谐音，人们便叫它“凤凰岭”。
<br/>悠久的历史，动人的传说。我爱我的家乡——木兰围场。"),
                'cover'        => "https://www.mlwch.com/upload/test/p7609240.jpg",
                'summary'      => "清代康熙年间，是一片水草肥美，林海雪原之地。后来被康熙帝封为皇家猎苑，也是清朝的军事训练基地。康熙，雍正，乾隆，先后在这里进行狩猎百余次，历史地位和政治地位相当高。",
                'text'         => "国家领导人对此的评语是：\"云的故乡，花的世界，林的海洋，水的源头\"。木兰围场是动物的天堂，影视剧的最佳外景地。",
                'access_token' => $this->weiboToken,
            );
            $ch  = curl_init();
            $url = "https://api.weibo.com/proxy/article/pubish.json";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $ret = curl_exec($ch);
            curl_close($ch);
            $ret = json_decode($ret, true);
            if ($ret['code'] == 100000) {
                echo '发布成功<br/>';
                echo '文章id：' . $ret['data']['object_id'];
                echo '<br/>文章地址：' . $ret['data']['url'];
            }
        } else {
            exit('参数错误，请重新授权登录');
        }
    }
}