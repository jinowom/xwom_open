<?php /* @var $model common\models\config\ConfigSms */ ?>
<div class="panel panel-default">
    <div class="panel-body">
       <table class="layui-table">
            <tr>
                <td>ID</td>
                <td><?= $model->id ?></td>
            </tr>
            <tr>
                <td>Order</td>
                <td><?= $model->order ?></td>
            </tr>
            <tr>
                <td>Sdk Com</td>
                <td><?= $model->sdk_com ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?= $model->description ?></td>
            </tr>
            <tr>
                <td>Access Key ID</td>
                <td><?= $model->access_key_id ?></td>
            </tr>
            <tr>
                <td>Access Key Secret</td>
                <td><?= $model->access_key_secret ?></td>
            </tr>
            <tr>
                <td>Access Key Sign</td>
                <td><?= $model->access_key_sign ?></td>
            </tr>
            <tr>
                <td>Model ID</td>
                <td><?= $model->model_id ?></td>
            </tr>
            <tr>
                <td>Send Message</td>
                <td><?= $model->send_message ?></td>
            </tr>
            <tr>
                <td>Sendvariable</td>
                <td><?= $model->sendvariable ?></td>
            </tr>
            <tr>
                <td>Created ID</td>
                <td><?= $model->created_id ?></td>
            </tr>
            <tr>
                <td>Updated ID</td>
                <td><?= $model->updated_id ?></td>
            </tr>
            <tr>
                <td>Created At</td>
                <td><?= $model->created_at ?></td>
            </tr>
            <tr>
                <td>Updated At</td>
                <td><?= $model->updated_at ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td><?= $model->status ?></td>
            </tr>
            <tr>
                <td>Is Del</td>
                <td><?= $model->is_del ?></td>
            </tr>
        </table>
   </div>
</div>