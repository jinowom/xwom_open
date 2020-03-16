<?php
use backend\widgets\Breadcrumbs;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="x-nav">
    <div>
    <span class="layui-breadcrumb">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </span>
    </div>
    <div style="position: absolute; right: 17px;top: 0px;">
     <a href="javascript:history.go(-1)" class="layui-btn layui-btn-small" style="margin-top:-3px;">
                    返回
                </a>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>

</div>