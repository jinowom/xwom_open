<?php /* @var $model common\models\log\ConfigBehaviorlog */ ?>
<div class="panel panel-default">
    <div class="panel-body">
         <table class="layui-table">
            <tr>
                <td>用户ID</td>
                <td><?= $model->user_id ?></td>
            </tr>
            <tr>
                <td>行为类别</td>
                <?php if($model->behavior == 1){ ?>
                    <td>页面浏览</td>
                <?php }else if($model->behavior == 2){ ?>
                    <td>查询数据</td>
                <?php }else if($model->behavior == 3){ ?>
                    <td>增加数据</td>
                <?php }else if($model->behavior == 4){ ?>
                    <td>修改数据</td>
                <?php }else if($model->behavior == 5){ ?>
                    <td>删除数据</td>
                <?php } ?>
            </tr>
            <tr>
                <td>提交方式</td>
                <td><?= $model->method ?></td>
            </tr>
            <tr>
                <td>模块</td>
                <td><?= $model->module ?></td>
            </tr>
            <tr>
                <td>控制器</td>
                <td><?= $model->controller ?></td>
            </tr>
            <tr>
                <td>方法</td>
                <td><?= $model->action ?></td>
            </tr>
            <tr>
                <td>提交url</td>
                <td><?= $model->url ?></td>
            </tr>
            <tr>
                <td>get数据</td>
                <td><?= $model->get_data ?></td>
            </tr>
            <tr>
                <td>post数据</td>
                <td><?= $model->post_data ?></td>
            </tr>
            <tr>
                <td>header数据</td>
                <td><?= $model->header_data ?></td>
            </tr>
            <tr>
                <td>Ip</td>
                <td><?= $model->ip ?></td>
            </tr>
            <tr>
                <td>备注</td>
                <td><?= $model->remark ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <?php if($model->status == 1){ ?>
                    <td>前台</td>
                <?php }else if($model->status == 2){ ?>
                    <td>后台</td>
                <?php }else if($model->status == 3){ ?>
                    <td>API</td>
                <?php }else if($model->status == 0){ ?>
                    <td>全局</td>
                <?php } ?>
            </tr>
            <tr>
                <td>添加时间</td>
                <td><?= $model->created_at ?></td>
            </tr>
        </table>
   </div>
</div>