<?php

use common\utils\ToolUtil;
/* @var $this yii\web\View */
$this->title = '部门权限';
$_csrfBackend = \Yii::$app->request->csrfToken;
$depAuth = \yii\helpers\Url::toRoute(['dep/dep-auth']);
?>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md3">
                <div class="layui-card"  style="background:#fff;">

                    <div class="layui-card-body ztree">
                        <p style="margin-left:7px;"> <a id="checkAll">全选</a> / <a id="cancelAll">取消</a></p>
                         <p class="ztree" id="dep_auth"></p>
                        <br/>
                        <p><a class="layui-btn layui-btn-xs" id="setting">设置</a><a class="layui-btn layui-btn-danger layui-btn-xs" id="cancel">取消</a></p>
                        <?php
                        $this->registerJsFile('@web/lib/ztree/js/jquery.ztree.core.js');
                        $this->registerJsFile('@web/lib/ztree/js/jquery.ztree.excheck.js');
                        $this->registerCssFile('@web/lib/ztree/css/zTreeStyle/zTreeStyle.css');
                        ?>
                        <script>
                            		<!--
                                    let setting = {
                                        check: {
                                            enable: true
                                        },
                                        data: {
                                            simpleData: {
                                                enable: true,
                                                idKey:"depid",
                                                pIdKey:"father_id",
                                            }
                                        },
                                        callback: {
                                            onCheck: onCheck
                                        }
                                    };
                                    var auth = '<?php  echo $auth;  ?>';
                                    auth = eval("("+auth+")");
                                    console.log(auth);
                                    var length = auth.length;
                                    console.log(length);
                                    let  zNodes  = [];
                                    for(var i=0;i<length;i++){
                                        let checked = false;
                                        if(auth[i].checked == 1){
                                            checked =true;
                                        }
                                        zNodes.push({depid:auth[i].depid,father_id:auth[i].father_id,name:''+ auth[i].name+'',true_name:''+ auth[i].true_name+'',checked:checked});
                                    }
                                    function onCheck(e, treeId, treeNode) {
                                        console.log(treeNode);
                                    }
                                    $(document).ready(function(){
                                        $.fn.zTree.init($("#dep_auth"), setting, zNodes);
                                        $('body').on('click','#checkAll',function(){
                                            var treeObj = $.fn.zTree.getZTreeObj("dep_auth");
                                            treeObj.checkAllNodes(true);
                                        });
                                        $('body').on('click','#cancelAll',function(){
                                            var treeObj = $.fn.zTree.getZTreeObj("dep_auth");
                                            treeObj.checkAllNodes(false);
                                        });
                                        $('body').on('click','#setting',function(){
                                            var treeObj = $.fn.zTree.getZTreeObj("dep_auth");
                                            var nodes = treeObj.getCheckedNodes(true);
                                            var authId = '<?php echo trim($_GET['authId']);    ?>';
                                            var description = '<?php echo trim($_GET['description']);    ?>';
                                            var roles ='';
                                            if(nodes){
                                                var length =nodes.length;
                                                for (var i=0;i<length;i++){
                                                    roles+=nodes[i].true_name+',';
                                                }
                                            }
                                            Cajax({
                                                type:"POST",
                                                url:'<?php  echo \yii\helpers\Url::toRoute(['dep/dep-auth']);    ?>',
                                                data:{_csrfBackend:'<?php echo \Yii::$app->request->csrfToken;   ?>',name:authId,description:description,roles:roles}
                                            },function () {},function(JsonData){
                                                var ic = (JsonData.status == true) ? icon.ICON_OK : icon.ICON_ERROR;
                                                layer.msg(JsonData.msg,{icon:ic},function () {
                                                    xadmin.father_reload();
                                                    xadmin.close();
                                                });
                                            });
                                        });
                                        $('body').on('click','#cancel',function(){
                                            parent.layer.closeAll();
                                        });
                                    });

                            </script>
                    </div>
                </div>
        </div>
    </div>
</div>
</div>