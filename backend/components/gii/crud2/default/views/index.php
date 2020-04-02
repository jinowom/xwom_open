<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\AppAsset;
use <?= $generator->indexWidgetType === 'grid' ? "backend\\grid\\GridView" : "backend\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use backend\widgets\Pjax;' : '' ?> <?php echo "\n";?>
AppAsset::register($this); <?php echo "\n";?>
$this->registerJs($this->render('js/index.js'));
/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
//$this->params['breadcrumbs'][] = $this->title;
?>
<!-- 面包屑 -->
<?php  echo "<?";?>
= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')<?php echo "?>\n";?>
<!-- 面包屑 -->
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <form class="layui-form layui-col-space5">
                                <div class="layui-inline layui-show-xs-block">
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
                                <?php if(!empty($generator->searchModelClass)): ?>
                                <?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
                                <?php endif; ?>
                            </form>
                        </div>
                        <div class="layui-card-header">
                            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                            <button class="layui-btn" onclick="xadmin.open('添加','<?= "<?=\yii\helpers\Url::toRoute" ?> (['<?= StringHelper::basename($generator->modelClass) ?>/create'])?>',600,400)"><i class="layui-icon"></i>添加</button>
                            <?= "<?= " ?>Html::a(<?= $generator->generateString('<i class= layui-icon></i>' . '添加 ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, ['create'], ['class' => 'layui-btn layui-default-add']) ?>
                        </div>
                        <div class="layui-card-body layui-table-body layui-table-main">
<?= $generator->enablePjax ? '<?php Pjax::begin(); ?>' : '' ?>
<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
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
                'filterModel' => $searchModel,
		'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],
			[
				'class' => 'backend\grid\CheckboxColumn',
				'checkboxOptions' => ['lay-skin'=>'primary','lay-filter'=>'choose'],
				'headerOptions' => ['width'=>'20','style'=> 'text-align: center;'],
				'contentOptions' => ['style'=> 'text-align: center;']
			],
                        <?php
                        $count = 0;
                        if (($tableSchema = $generator->getTableSchema()) === false) {
                            foreach ($generator->getColumnNames() as $name) {
                                if (++$count < 10) {
                                    echo "                    '" . $name . "',\n";
                                } else {
                                    echo "                   // '" . $name . "',\n";
                                }
                            }
                        } else {
                            foreach ($tableSchema->columns as $column) {
                                $format = $generator->generateColumnFormat($column);
                                if (++$count < 10) {
                                    echo "                     '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                                } else {
                                    echo "                     // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                                }
                            }
                        }
                        ?>

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
                                                                    return Html::a('查看', $url, ['class' => "layui-btn layui-btn-xs layui-default-view"]);
                                        },
                                        'update' => function ($url, $model, $key) {
                                                                    //return Html::a('修改', Url::to(['update','id'=>$model->id]), ['class' => "layui-btn layui-btn-normal layui-btn-xs layui-default-update"]);
                                                                    return Html::a('修改', $url, ['class' => "layui-btn layui-btn-normal layui-btn-xs layui-default-update"]);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                                //return Html::a('删除', Url::to(['delete','id'=>$model->id]), ['class' => "layui-btn layui-btn-danger layui-btn-xs layui-default-delete"]);
                                                return Html::a('删除', $url, ['class' => "layui-btn layui-btn-danger layui-btn-xs layui-default-delete"]);
                                        }
				]
            ],
        ],
    ]); ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>
<?= $generator->enablePjax ? '<?php Pjax::end(); ?>' : '' ?>   
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

       /*用户-停用*/
      function member_stop(obj,id){
          layer.confirm('确认要停用吗？',function(index){

              if($(obj).attr('title')=='启用'){

                //发异步把用户状态进行更改
                $(obj).attr('title','停用')
                $(obj).find('i').html('&#xe62f;');

                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                layer.msg('已停用!',{icon: 5,time:1000});

              }else{
                $(obj).attr('title','启用')
                $(obj).find('i').html('&#xe601;');

                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                layer.msg('已启用!',{icon: 5,time:1000});
              }
              
          });
      }

      /*用户-删除*/
      function member_del(obj,id){
          layer.confirm('确认要删除吗？',function(index){
              //发异步删除数据
              $(obj).parents("tr").remove();
              layer.msg('已删除!',{icon:1,time:1000});
          });
      }



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