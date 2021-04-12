<?php /* @var $model common\models\config\ConfigOss */ ?>
<div class="panel panel-default">
    <div class="panel-body">
    <table class="layui-table">
                            <tr>
                        <td>阿里ID</td>
                        <td><?= $model->access_key_id ?></td>
                    </tr>
                    <tr>
                        <td>阿里API秘钥</td>
                        <td><?= $model->access_key_secret ?></td>
                    </tr>
                    <tr>
                        <td>阿里bucket域名</td>
                        <td><?= $model->bucket ?></td>
                    </tr>
                    <tr>
                        <td>sdk配置项地域节点</td>
                        <td><?= $model->endpoint ?></td>
                    </tr>
                    <tr>
                        <td>oss地址</td>
                        <td><?= $model->url ?></td>
                    </tr>
                    <tr>
                        <td>本地地址</td>
                        <td><?= $model->local_url ?></td>
                    </tr>
                    <tr>
                        <td>描述</td>
                        <td><?= $model->description ?></td>
                    </tr>
        </table>
   </div>
</div>