<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\AppAsset;
use backend\grid\GridView;
use backend\widgets\Pjax; 
AppAsset::register($this); 
//$this->registerJs($this->render('js/index.js'));
/* @var $this yii\web\View */
/* @var $searchModel common\models\config\ConfigBehaviorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Config Behaviors');
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- 面包屑 -->
<?= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')?>
<!-- 面包屑 -->
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <form class="layui-form layui-col-space5">
                                <!--下面不需要，则需要删除-->
                                <!--<div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input"  autocomplete="off" placeholder="开始日" name="start" id="start">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input"  autocomplete="off" placeholder="截止日" name="end" id="end">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="username"  placeholder="请输入用户名" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                                </div>
                                -->
                                
                                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                                
                            </form>
                        </div>
                        <div class="layui-card-header">
                            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                            <?= Html::Button('<i class="layui-icon"></i>添加',
                                    [
                                       'onclick' => 'xadmin.open("添加", "'.Url::toRoute(['create']).'",500,550)',
                                       'data-target' => '#create-modal',
                                       'class' => 'layui-btn',
                                       'id' => 'modalButton',
   
                                    ]
                                ) 
                            ?>
                            <!--<?= Html::a(Yii::t('app', '<i class= layui-icon></i>添加 Config Behavior'), ['create'], ['class' => 'layui-btn layui-default-add']) ?> -->                        </div>
                        <div class="layui-card-body layui-table-body layui-table-main">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'options' => ['class' => 'layui-table-box ','style'=>'overflow:auto', 'id' => 'grid'],
                //'layout'=> '{items}<div class="layui-table-page"><div id="layui-table-page1"><div class="layui-box layui-laypage layui-laypage-default" id="layui-laypage-1">{pager}</div></div></div>',
                'layout'=> '{items}<div style="margin: 10px 0 0 10px;">{pager}</div>',
		'tableOptions'=> ['class'=>'layui-table','style'=>'width: 100%; '],
		'pager' => [
                        //'options'=>['class'=>'hidden'],//关闭自带分页
			'options'=>['class'=>'layuipage pull-left'],
				'prevPageLabel' => '上一页',
				'nextPageLabel' => '下一页    ',
				'firstPageLabel'=>'首页    ',
				'lastPageLabel'=>'尾页',
				'maxButtonCount'=>5,
                ],
                //GridView控制行样式 rowOptions属性
                
                //'showFooter'=>true,//显示底部（就是多了一栏），默认是关闭的
                //'filterModel' => $searchModel,
		'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],
			[
				'class' => 'backend\grid\CheckboxColumn',
				'checkboxOptions' => ['lay-skin'=>'primary','lay-filter'=>'choose'],
				'headerOptions' => ['width'=>'20','style'=> 'text-align: center;'],
				'contentOptions' => ['style'=> 'text-align: center;']
			],
                                             'id',
                     'app_id',
                     'url:url',
                     'method',
                     'behavior',
                     'action',
                     'level',
                     'is_record_post',
                     'is_ajax',
                     // 'remark',
                     // 'addons_name',
                     // 'is_addon',
                     // 'status',
                     // 'created_at',
                     // 'updated_at',
                     // 'created_id',
                     // 'updated_id',

            [
            'header' => '<div class="layui-table-cell">操作</div>',
				'class' => 'yii\grid\ActionColumn',
				'headerOptions' => [
					'width' => '20%'
				],
                                'template' =>'<div class="layui-table-cell"> {view} {update} {delete} </div>',
				'buttons' => [
                                        'view' => function ($url, $model, $key){
                                            //return Html::a('查看', Url::to(['view','id'=>$model->id]), ['class' => "layui-btn layui-btn-xs layui-default-view"]);
                                            return Html::Button('查看',
                                                    [
                                                    'onclick' => 'xadmin.open("查看", "'.$url.'",500,550)',
                                                    'data-target' => '#view-modal',
                                                    'class' => 'layui-btn layui-btn-xs layui-default-view',
                                                    'id' => 'modalButton',
                                                    ]
                                                ); 
                                                    
                                        },
                                        'update' => function ($url, $model, $key) {
                                            //return Html::a('修改', Url::to(['update','id'=>$model->id]), ['class' => "layui-btn layui-btn-normal layui-btn-xs layui-default-update"]);
                                            return Html::Button('修改',
                                                    [
                                                    'onclick' => 'xadmin.open("修改", "'.$url.'",500,550)',
                                                    'data-target' => '#update-modal',
                                                    'class' => 'layui-btn layui-btn-normal layui-btn-xs layui-default-update',
                                                    'id' => 'modalButton',
                                                    ]
                                                );  
                                                    
                                        },

                                        'delete'=>function($url,$model,$key)
                                            {
                                                $options=[
                                                    'title'=>Yii::t('yii', '删除'),
                                                    'aria-label'=>Yii::t('yii','删除'),
                                                    'data-confirm'=>Yii::t('yii','Are you sure you want to delete this item?'),
                                                    'data-method'=>'post',
                                                    'data-pjax'=>'0',
                                                    'class'=>'layui-btn layui-btn-danger layui-btn-xs layui-default-delete'
                                                    ];
                                                    return Html::a('删除',$url,$options); 
                                                //if($model->status == 0){//不同的视图，需要修改这里字段名称 或者不用判断，直接 return
                                                    //return Html::a('删除',$url,$options); 
                                                    //} else {
                                                     //  return Html::a('已审',$url,$options);  
                                                   // }
                                            },
				]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>   
                        </div>

                    </div>
                </div>
            </div>
        </div> 

    <script>
      layui.use(['laydate','form'], function(){
        var laydate = layui.laydate;
        var  form = layui.form;


        // 监听全选
        form.on('checkbox(checkall)', function(data){

          if(data.elem.checked){
            $('tbody input').prop('checked',true);
          }else{
            $('tbody input').prop('checked',false);
          }
          form.render('checkbox');
        }); 
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });


      });
      /*用户-删除*/
      function delAll (argument) {
        var ids = [];

        // 获取选中的id 
        $('tbody input').each(function(index, el) {
            if($(this).prop('checked')){
               ids.push($(this).val())
            }
        });
  
        layer.confirm('确认要删除吗？'+ids.toString(),function(index){
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }
    </script>