<?php
use common\utils\ToolUtil;
/* @var $this yii\web\View */

$this->title = '添加资源稿库';
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
<style>
    body{background: #ffffff;}
</style>
<div class="layui-fluid layui-card" style="padding: 30px 0px;">
    <div class="layui-row">
        <form class="layui-form" action="<?=\yii\helpers\Url::toRoute(['resources/add-new'])?>" method="post" >
            <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
            <input name="id" type="hidden" value="<?=ToolUtil::getSelectType($model,'id')?>" />
            <div class="layui-row layui-col-space10 layui-form-item">
                <div class="layui-col-lg4 layui-col-sm6 layui-col-xs6">
                    <label class="layui-form-label" style="width: auto"><span class="x-red">*</span>稿件类型：</label>
                    <div class="layui-input-block">
                        <select lay-reqText="请选择稿件类型" lay-search="" name="new[newstype_id]" lay-verify="required" lay-filter="aihao">
                            <option value="">请选择稿件类型</option>
                            <?php foreach ($types as $key => $type):?>
                                <option value="<?=$key?>" <?=ToolUtil::valCompareVal($key,$model['newstype_id'],'selected')?> ><?=$type?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="layui-col-lg4 layui-col-sm6 layui-col-xs6">
                    <label class="layui-form-label" style="width: auto">所属部门：</label>
                    <div class="layui-input-block">
                        <select lay-reqText="请选择稿件所属部门" lay-search=""  name="new[dep_id]" lay-verify="required" lay-filter="aihao">
                            <option value="">请选择稿件所属部门</option>
                            <?php foreach ($deps as $k => $dep):?>
                                <option value="<?=ToolUtil::getIsset($dep->depid)?>" <?=ToolUtil::valCompareVal($dep->depid,$depId,'selected')?>><?=ToolUtil::getSelectType($dep->getUnit(),'name').'-'.ToolUtil::getIsset($dep->name)?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="layui-col-lg4 layui-col-sm6 layui-col-xs6">
                    <label class="layui-form-label" style="width: auto">所属团队：</label>
                    <div class="layui-input-block">
                        <select lay-reqText="请选择稿件所属团队" lay-search="" name="new[team_id]" lay-verify="required" lay-filter="aihao">
                            <option value="">请选择稿件所属团队</option>
                            <?php foreach ($items as $kk => $item):?>
                                <option value="<?=ToolUtil::getIsset($item->teamid)?>" <?=ToolUtil::valCompareVal($item->teamid,$teamId,'selected')?>><?=ToolUtil::getSelectType($item->getUnit(),'name').'-'.ToolUtil::getIsset($item->name)?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-row layui-col-space10 layui-form-item">
                <div class="layui-col-lg6 layui-col-sm8 layui-col-xs8">
                    <label for="title_eyebrow" class="layui-form-label" style="width: auto"><span class="x-red">*</span>稿件引题：</label>
                    <div class="layui-input-block">
                        <input type="text" id="title_eyebrow" lay-max="100" name="new[title_eyebrow]" lay-reqText="稿件引题不能为空" lay-verify="required|max" placeholder="请输入引题" autocomplete="off" class="layui-input" value="<?=ToolUtil::getSelectType($model,'title_eyebrow')?>" />
                    </div>
                </div>
            </div>
            <div class="layui-row layui-col-space10 layui-form-item">
                <div class="layui-col-lg6 layui-col-sm8 layui-col-xs8">
                    <label for="title" class="layui-form-label" style="width: auto"><span class="x-red">*</span>稿件标题：</label>
                    <div class="layui-input-block">
                        <input type="text" id="title" name="new[title]" lay-max="100" lay-verify="required" placeholder="请输入稿件标题" autocomplete="off" class="layui-input" value="<?=ToolUtil::getSelectType($model,'title')?>" />
                    </div>
                </div>
            </div>
            <div class="layui-row layui-col-space10 layui-form-item">
                <div class="layui-col-lg6 layui-col-sm8 layui-col-xs8">
                    <label for="title_sub" class="layui-form-label" style="width: auto"><span class="x-red">*</span>稿件副题：</label>
                    <div class="layui-input-block">
                        <input type="text" id="title_sub" name="new[title_sub]" lay-max="100" lay-verify="required" placeholder="请输入稿件副题" autocomplete="off" class="layui-input" value="<?=ToolUtil::getSelectType($model,'title_sub')?>" />
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-col-lg6 layui-col-sm8 layui-col-xs8">
                    <label for="real_name" class="layui-form-label" style="width: auto">
                        <span class="x-red">*</span>作者名称：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="author" name="new[author]" value="<?=ToolUtil::getSelectType($model,'author','')?>" placeholder="请输入真实姓名" required="" lay-verify="required" lay-reqText="请输入真实姓名"
                               autocomplete="off" class="layui-input" />
                    </div>
                </div>
            </div>
            <div class="layui-row layui-col-space10 layui-form-item">
                <div class="layui-col-lg6 layui-col-sm8 layui-col-xs8">
                    <label for="foreword" class="layui-form-label" style="width: auto"><span class="x-red">*</span>稿件引言：</label>
                    <div class="layui-input-block">
                        <textarea id="foreword" name="new[foreword]" placeholder="请输入稿件引言" class="layui-textarea" name="desc"><?=ToolUtil::getSelectType($model,'foreword')?></textarea>
                    </div>
                </div>
            </div>
            <div class="layui-row layui-col-space10 layui-form-item">
                <div class="layui-col-lg6 layui-col-sm8 layui-col-xs8">
                    <label for="content" class="layui-form-label" style="width: auto"><span class="x-red">*</span>稿件内容：</label>
                    <div class="layui-input-block">
                        <?=\moxuandi\kindeditor\KindEditor::widget([
                            'name' => 'content',
                            'value' => "{$model['content']}",
                            'editorOptions' => [
                                'width' => '1000',
                                'height' => 500,
                                'afterBlur' => new \yii\web\JsExpression('function(){ 
                                    this.sync();
                                }')
                            ]
                        ]);?>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="add">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
$getAdminList = \yii\helpers\Url::toRoute(['get-admin-list']);
$update = \yii\helpers\Url::toRoute(['auth/update-admin-status']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$tableJs = <<<JS
    $(function() {
          layui.use(['form', 'layer'],function() {
            $ = layui.jquery;  var form = F(layui.form), layer = layui.layer;
            //自定义验证规则
            form.verify({
            });
            //监听提交
            var sFun = function (jsonData) {
                    layer.close(load);
                    var ic = (jsonData.status == true) ? icon.ICON_OK : icon.ICON_ERROR;
                    layer.msg(jsonData.msg,{icon:ic},function () {
                        if(jsonData.status == true){
                            xadmin.father_reload();
                            xadmin.close();                    
                        }
                    });
            };
            form.submit('add','',sFun);
        });
    })
JS;
$this->registerJs($tableJs,\yii\web\View::POS_END);
?>