<?php

/* @var $this yii\web\View */
use common\utils\ToolUtil;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = Yii::$app->name.'后台登录,欢迎页';
?>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <blockquote class="layui-elem-quote">欢迎管理员：
                        <span class="x-red"><?php  echo $user = Yii::$app->user->identity->username;  ?></span>！当前时间:<?php echo date('Y-m-d H:i:s',time());   ?>
                    </blockquote>
                </div>
            </div>
        </div>
        <!--
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">数据统计</div>
                <div class="layui-card-body ">
                    <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>文章数</h3>
                                <p>
                                    <cite>66</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>会员数</h3>
                                <p>
                                    <cite>12</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>回复数</h3>
                                <p>
                                    <cite>99</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>商品数</h3>
                                <p>
                                    <cite>67</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>文章数</h3>
                                <p>
                                    <cite>67</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md2 layui-col-xs6 ">
                            <a href="javascript:;" class="x-admin-backlog-body">
                                <h3>文章数</h3>
                                <p>
                                    <cite>6766</cite></p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        -->
        <!--
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">下载
                    <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                <div class="layui-card-body  ">
                    <p class="layuiadmin-big-font">33,555</p>
                    <p>新下载
                                <span class="layuiadmin-span-color">10%
                                    <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                    </p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">下载
                    <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                <div class="layui-card-body ">
                    <p class="layuiadmin-big-font">33,555</p>
                    <p>新下载
                                <span class="layuiadmin-span-color">10%
                                    <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                    </p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">下载
                    <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                <div class="layui-card-body ">
                    <p class="layuiadmin-big-font">33,555</p>
                    <p>新下载
                                <span class="layuiadmin-span-color">10%
                                    <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                    </p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">下载
                    <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                <div class="layui-card-body ">
                    <p class="layuiadmin-big-font">33,555</p>
                    <p>新下载
                            <span class="layuiadmin-span-color">10%
                                <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                    </p>
                </div>
            </div>
        </div>
        -->
        <div class="layui-col-md6 layui-col-sm6 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">环境配置</div>
                <div class="layui-card-body ">
                    <table class="layui-table">
                        <tbody>
                        <tr>
                            <th>PHP版本</th>
                            <td><?= phpversion(); ?></td></tr>
                        <tr>
                            <th>Mysql版本</th>
                            <td><?= Yii::$app->db->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION); ?></td></tr>
                        <tr>
                            <th>解析引擎</th>
                            <td><?= $_SERVER['SERVER_SOFTWARE']; ?></td></tr>
                        <tr>
                            <th>数据库大小</th>
                            <td><?= Yii::$app->formatter->asShortSize($mysql_size, 2); ?></td></tr>
                        <tr>
                            <th>附件目录</th>
                            <td><?= Yii::$app->request->hostInfo . Yii::getAlias('@attachurl'); ?>/</td></tr>
                        <tr>
                        <tr>
                            <th>附件目录大小</th>
                            <td><?= Yii::$app->formatter->asShortSize($attachment_size, 2); ?></td></tr>
                        <tr>
                            <th>超时时间</th>
                            <td><?= ini_get('max_execution_time'); ?>秒</td></tr>
                        <tr>
                            <th>客户端信息</th>
                            <td><?= $_SERVER['HTTP_USER_AGENT'] ?></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="layui-col-md6 layui-col-sm6 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">系统信息</div>
                <div class="layui-card-body ">
                    <table class="layui-table">
                        <tbody>
                        <tr>
                            <th>系统全称</th>
                            <td><?= Yii::$app->params['exploitFullName']; ?></td></tr>
                        <tr>
                            <th>系统版本</th>
                            <td><?= Yii::$app->version; ?></td>
                        </tr>
                        <tr>
                            <th>临时版本日期</th>
                            <td><?= Yii::$app->params['exploitDate']?></td>
                        </tr>
                        <tr>
                            <th>YII开发框架版本</th>
                            <td><?= Yii::getVersion(); ?><?php if (YII_DEBUG) echo ' (开发模式)'; ?></td></tr>
                        <tr>
                            <th>开发者官网</th>
                            <td><?= Yii::$app->params['exploitOfficialWebsite']?></td></tr>
                        <tr>
                            <th>开发者QQ群</th>
                            <td><a href="" target="_blank">xxxx</a></td></tr>
                        <tr>
                            <th>版本包下载</th>
                            <td><?= Yii::$app->params['exploitGitHub']?></td></tr>
                        <tr>
                            <th>开发包类型</th>
                            <td><?= Yii::$app->params['exploitDeveloper']?></td>
                        </tr>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
       
       
    <div class="layui-col-md12">
        <div class="layui-card">
            <div class="layui-card-header">PHP信息</div>
            <div class="layui-card-body">
                <table class="layui-table">
                    <tbody>
                    <tr>
                        <th>PHP执行方式</th>
                        <td><?= php_sapi_name(); ?></td>
                    </tr>
                    <tr>
                        <th>扩展支持</th>
                        <td>
                            <?= extension_loaded('gd')
                                ? '<span class="label label-primary">gd支持</span>'
                                : '<span class="label label-default">gd不支持</span>'; ?>
                            <?= extension_loaded('imagick')
                                ? '<span class="label label-primary">imagick支持</span>'
                                : '<span class="label label-default">imagick不支持</span>'; ?>
                            <?= extension_loaded('curl')
                                ? '<span class="label label-primary">curl支持</span>'
                                : '<span class="label label-default">curl不支持</span>'; ?>
                            <?= extension_loaded('fileinfo')
                                ? '<span class="label label-primary">fileinfo支持</span>'
                                : '<span class="label label-default">fileinfo不支持</span>'; ?>
                            <?= extension_loaded('intl')
                                ? '<span class="label label-primary">intl支持</span>'
                                : '<span class="label label-default">intl不支持</span>'; ?>
                            <?= extension_loaded('mbstring')
                                ? '<span class="label label-primary">mbstring支持</span>'
                                : '<span class="label label-default">mbstring不支持</span>'; ?>
                            <?= extension_loaded('intl')
                                ? '<span class="label label-primary">exif支持</span>'
                                : '<span class="label label-default">exif不支持</span>'; ?>
                            <?= extension_loaded('openssl')
                                ? '<span class="label label-primary">openssl支持</span>'
                                : '<span class="label label-default">openssl不支持</span>'; ?>
                            <?= extension_loaded('Zend OPcache')
                                ? '<span class="label label-primary">opcache支持</span>'
                                : '<span class="label label-default">opcache不支持</span>'; ?>
                            <?= extension_loaded('memcache')
                                ? '<span class="label label-primary">memcache支持</span>'
                                : '<span class="label label-default">memcache不支持</span>'; ?>
                            <?= extension_loaded('memcached')
                                ? '<span class="label label-primary">memcached支持</span>'
                                : '<span class="label label-default">memcached不支持</span>'; ?>
                            <?= extension_loaded('redis')
                                ? '<span class="label label-primary">redis支持</span>'
                                : '<span class="label label-default">redis不支持</span>'; ?>
                            <?= extension_loaded('swoole')
                                ? '<span class="label label-primary">swoole支持</span>'
                                : '<span class="label label-default">swoole不支持</span>'; ?>
                            <?= extension_loaded('mongodb')
                                ? '<span class="label label-primary">mongodb支持</span>'
                                : '<span class="label label-default">mongodb不支持</span>'; ?>
                            <?= extension_loaded('amqp')
                                ? '<span class="label label-primary">amqp支持</span>'
                                : '<span class="label label-default">amqp不支持</span>'; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>禁用的函数</th>
                        <td >
                            <?php if (is_array($disable_functions)){ ?>
                                <?php foreach ($disable_functions as $function){ ?>
                                    <span class="label label-default"><?= $function; ?></span>
                                <?php } ?>
                            <?php }else{ ?>
                                <span class="label label-default"><?= $disable_functions; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>脚本内存限制</th>
                        <td><?= ini_get('memory_limit'); ?></td>
                    </tr>
                    <tr>
                        <th>文件上传限制</th>
                        <td><?= ini_get('upload_max_filesize'); ?></td>
                    </tr>
                    <tr>
                        <th>Post数据最大尺寸</th>
                        <td><?= ini_get('post_max_size'); ?></td>
                    </tr>
                    <tr>
                        <th>Socket超时时间</th>
                        <td><?= ini_get('default_socket_timeout'); ?> 秒</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="layui-col-md12">
        <div class="layui-card">
            <div class="layui-card-header">开发团队</div>
            <div class="layui-card-body ">
                <table class="layui-table">
                    <tbody>
                    <tr>
                        <th>版权所有</th>
                        <td>xwom
                            <a href="https://www.jinostart.com/" target="_blank">访问官网</a></td>
                    </tr>
                    <tr>
                        <th>开发者</th>
                        <td>Charles  Weihua  YanchengLiu </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        
        <style id="welcome_style"></style>
    </div>
</div>
</div>
