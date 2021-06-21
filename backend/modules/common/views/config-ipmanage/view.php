<?php /* @var $model common\models\config\ConfigIpmanage */ ?>
<div class="panel panel-default">
    <div class="panel-body">
    <table class="layui-table">
                            <tr>
                        <td>IP地址</td>
                        <td><?= $model->ip ?></td>
                    </tr>
                    <tr>
                        <td>状态</td>
                        <td><?= $model->status ?></td>
                    </tr>
                    <tr>
                        <td>开始时间</td>
                        <td><?= $model->start_time ?></td>
                    </tr>
                    <tr>
                        <td>结束时间</td>
                        <td><?= $model->end_time ?></td>
                    </tr>
                    <tr>
                        <td>添加时间</td>
                        <td><?= $model->created_at ?></td>
                    </tr>
                    <tr>
                        <td>修改时间</td>
                        <td><?= $model->updated_at ?></td>
                    </tr>
                    <tr>
                        <td>添加者</td>
                        <td><?= $model->created_id ?></td>
                    </tr>
                    <tr>
                        <td>修改者</td>
                        <td><?= $model->updated_id ?></td>
                    </tr>
        </table>
   </div>
</div>