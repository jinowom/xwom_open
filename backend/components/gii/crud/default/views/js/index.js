layui.config({
	base : "js/"
}).use(['form','layer','jquery'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;
 
	//表格操作 [添加、查看、修改、删除、批量删除、全选]

	//1、全选功能
	form.on('checkbox(allChoose)', function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		child.each(function(index, item){
			item.checked = data.elem.checked;
		});
		form.render('checkbox');
	});

	//2、通过判断文章是否全部选中来确定表头全选按钮是否选中
	form.on("checkbox(choose)",function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		var childChecked = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"]):checked')
		if(childChecked.length === child.length){
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = true;
		}else{
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = false;
		}
		form.render('checkbox');
	});
    
	//3、添加
//	$(window).one("resize",function(){
//		$(".layui-default-add").click(function(){
//			var index = layui.layer.open({
//				title : "添加文章",
//				type : 2,
//                area: ['50%', '50%'], //宽高
//                content : "<?= yii\helpers\Url::to(['create']); ?>",
//                success : function(layero, index){
//                        setTimeout(function(){
//                                layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
//                                        tips: 3
//                                });
//                        },500);
//                },
//                end: function () {
//                    location.reload();
//                }                
//			});	
//			layui.layer.full(index);
//		});
//	}).resize();
	//添加
        //改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
	$("body").on("click",".layui-default-add",function(){
        var href = $(this).attr("href");
        console.log(href);
        var index = layui.layer.open({
            title : "添加",
            type : 2,
            area: ['600px', '500px'], //宽高  ['100%', '100%']  或['600px', '500px'],//好像哪里不对头，自定义大小没作用
            content : href,
            success : function(layero, index){
                setTimeout(function(){
                    layui.layer.tips('点击此处返回', ' .layui-layer-ico .layui-layer-setwin .layui-layer-close ', {
                        tips: 3
                    });
                },500);
            }
        });	
        layui.layer.full(index);//全屏当前弹出层
        return false;
	});
    
    //4、查看
	$("body").on("click",".layui-default-view",function(){
        var href = $(this).attr("href");
        console.log(href);
        var index = layui.layer.open({
            title : "查看文章",
            type : 2,
            area: ['100%', '100%'], //宽高  ['100%', '100%']  或['600px', '500px'],
            content : href,
            success : function(layero, index){
                setTimeout(function(){
                    layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500);
            }
        });	
        layui.layer.full(index);//全屏当前弹出层
        return false;
	});
    
    //5、修改
	$("body").on("click",".layui-default-update",function(){  
        var href = $(this).attr("href");
        console.log(href);
        var index = layui.layer.open({
            title : "修改文章",
            type : 2,
            area: ['100%', '100%'],
            content : href,
            success : function(layero, index){
                setTimeout(function(){
                    layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500);
            },
            end: function () {
                location.reload();
            }
        });	
        layui.layer.full(index); //全屏当前弹出层
        return false;
	});

    //6、删除
	$("body").on("click",".layui-default-delete",function(){
        var href = $(this).attr("href");
		layer.confirm('确定删除此文章吗？',{icon:3, title:'提示信息'},function(index){
            $.post(href,function(data){
                if(data.code===200){
                    layer.msg(data.msg);
                    layer.close(index);
                    setTimeout(function(){
                       location.reload();
                    },500);
                }else{
                    layer.close(index);
                    layer.msg(data.msg);
                }
            },"json").fail(function(a,b,c){
                if(a.status==403){
                    layer.msg('没有权限');
                }else{
                    layer.msg('系统错误');
                }
            });
		});
        return false;
	});
});
