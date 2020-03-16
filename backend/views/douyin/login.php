<?php

/* @var $this yii\web\View */

$this->title = '微博授权登录';
?>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">授权登录</div>
                <div class="layui-card-body ">
                    <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                        <li class="layui-col-md2 layui-col-xs6">
                            <a href='https://api.weibo.com/oauth2/authorize?client_id=2538020796&redirect_uri=https://www.mlwch.com/custom/weiboCallBack.html&response_type=code&state=spacing' class="x-admin-backlog-body">
                                <p>
                                    <cite>登录</cite></p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>