<?php
use common\utils\ToolUtil;
/* @var $this yii\web\View */

$this->title = '取稿';
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
<style>
    body{background: #ffffff;}
</style>
<div class="layui-fluid layui-card" style="padding: 30px 0px;">
    <div class="layui-row">
        <form class="layui-form" action="<?=\yii\helpers\Url::toRoute(['resources/get-news'])?>" method="post" >
            <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
<!--            <input name="id" type="hidden" value="--><?//=ToolUtil::getSelectType($model,'id')?><!--" />-->
            <div class="layui-row layui-col-space10 layui-form-item">
                <div class="layui-col-lg4 layui-col-sm6 layui-col-xs6">
                    <label class="layui-form-label" style="width: auto"><span class="x-red">*</span>取稿类型：</label>
                    <div class="layui-input-block">
                        <select id="newstype_id" lay-reqText="请选择稿件类型" readonly name="new[newstype_id]" lay-verify="required" lay-filter="selectType">
                            <?php if($typeNames):?>
                                <?php foreach ($typeNames as $k => $type):?>
                                    <option value="<?=$k?>"><?=$type?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-row layui-col-space10 layui-form-item">
                <div class="layui-col-lg6 layui-col-sm8 layui-col-xs8">
                    <label for="title_eyebrow" class="layui-form-label" style="width: auto"><span class="x-red">*</span>稿件用途：</label>
                    <div class="layui-input-block">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <?php if($typeNames):?>
                                    <?php foreach ($typeNames as $k => $type):?>
                                        <li id="li_<?=$k?>" style="pointer-events: none;" value="<?=$k?>" class="select <?=($k==2)?"layui-this":""?>"><?=$type?></li>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </ul>
                            <div class="layui-tab-content" style="border-left: 1px #e6e6e6 solid;border-bottom: 1px #e6e6e6 solid; border-right: 1px #e6e6e6 solid">
                                <?php if($typeNames):?>
                                    <?php foreach ($typeNames as $kk => $vv):?>
                                        <div id="div_<?=$kk?>" class="select layui-tab-item <?=($kk==2)?"layui-show":""?>">
                                            <select id="d_s_<?=$kk?>" lay-reqText="请选择<?=($kk==2)?"报纸":"栏目"?>" name="new[type<?=$kk?>]" lay-verify="<?=($kk==2)?"required":""?>" lay-filter="type">
                                                <option value="">请选择<?=($kk==2)?"报纸":"栏目"?></option>
                                                <?php foreach ($typeData[$kk] as $ktype => $vData){?>
                                                    <option value="<?=$ktype?>"><?=$vData?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <div id="z_div_<?=$kk?>" class="select layui-tab-item">
                                            <select name="new[ztype<?=$kk?>]" id="z_d_s_<?=$kk?>" lay-verify="<?=($kk==2)?"required":""?>">

                                            </select>
                                        </div>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-row layui-col-space10 layui-form-item">
                <label for="title_eyebrow" class="layui-form-label" style="width: auto">已选择稿件：</label>
                <div class="layui-input-block" id="s_ns">
                    <table class="layui-table" width="100%">
                        <colgroup>
                            <col width="95%">
                            <col>
                        </colgroup>
                        <tbody>
                        <?php if(isset($newsData)):?>
                            <?php foreach ($newsData as $nk => $nv):?>
                                <tr id="tr_<?=$nk?>">
                                    <td><input type="hidden" id="id_<?=$nk?>" name="ids[]" value="<?=$nk?>" /><?=$nv?></td>
                                    <td style="text-align: center"><a class="layui-btn layui-btn-danger layui-btn-xs" onclick="delR(<?=$nk?>)">删除</a></td>
                                <tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="h_ids" lay-reqText="取稿的稿件不能为空" lay-verify="required" value="1" />
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
$getTypeData = \yii\helpers\Url::toRoute(['get-news-type-data']);
$update = \yii\helpers\Url::toRoute(['auth/update-admin-status']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$tableJs = <<<JS
    function delR(id){
        layer.confirm('确认要删除吗？', function(index){
            $("#tr_"+id).remove();
            $("#h_ids").val();
            layer.closeAll();
            var ids = $("input[name^='ids']");
            if(ids.length < 1){
                $("#h_ids").val(null);
                $("#s_ns").html("<b class='x-red' style='font-size:18px;'>请选择取稿的稿件</b>");
            }
        });
    }
    $(function() {
          layui.use(['form', 'layer','element','jquery'],function() {
            $ = layui.jquery; var f = layui.form;  var form = F(layui.form), layer = layui.layer,element = layui.element;
            f.on('select(selectType)', function(data){
                var key = data.value;
                $("li.select").removeClass("layui-this");
                $("div.select").removeClass("layui-show").find('select').attr('lay-verify','');
                $("#li_"+key).addClass("layui-this");
                $("#div_"+key).addClass("layui-show").find('select#d_s_'+key).attr('lay-verify','required');
                $("#z_div_"+key).addClass("layui-show").find('select#z_d_s_'+key).attr('lay-verify','required');
            });
            //自定义验证规则
            form.verify({
            });
            //监听提交
            var sFun = function (jsonData) {
                    layer.close(load);
                    var ic = (jsonData.status == true) ? icon.ICON_OK : icon.ICON_ERROR;
                    layer.msg(jsonData.msg,{icon:ic},function () {
                        if(jsonData.status == true){
                            xadmin.close();                    
                        }
                    });
            };
            form.submit('add','',sFun);
            
            f.on('select(type)',function(data){
               var val = data.value;
               var newstypeId = $("#newstype_id").val();
               Cajax({
                    type:"POST",
                    url:"$getTypeData",
                    data:{_csrfBackend:'$_csrfBackend',val:val,type:newstypeId}
                },function(){},function(JsonData){
                   if(JsonData != ''){
                       var s = '<option value="">请选择</option>';
                       for (var i in JsonData){
                           s += '<option value="'+i+'">'+JsonData[i]+'</option>';
                       }
                       $("#z_div_"+newstypeId).show();
                       $("#z_d_s_"+newstypeId).empty().append(s)
                       f.render('select');
                   }
                });
            });
        });
    })
JS;
$this->registerJs($tableJs,\yii\web\View::POS_END);
?>