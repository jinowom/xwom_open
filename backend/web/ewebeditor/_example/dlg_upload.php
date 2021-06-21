<?php header("content-Type: text/html; charset=utf-8");?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>eWebEditor ： 输入框调用上传文件对话框示例</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='example.css'>
</head>
<body>

<p><b>导航 ： <a href="default.php">示例首页</a> &gt; 输入框调用上传文件对话框示例</b></p>
<p>通过使用这个功能，您可以在任意的输入框中调用编辑器自带的上传文件功能，上传文件和浏览服务器上原已上传的文件，并返回输入框上传文件的路径文件名。您可以在一个网页的任意多个地方调用，支持分类型上传及缩略图调用，并可在编辑器后台设置。一行调用代码，即可为您的网站加入上传文件功能。</p>
<p>点击下面的“上传”按钮，看一下效果，不同的按钮允许上传的文件类型及文件大小不同。</p>


<!-- 第一步：引用接口js文件 -->
<script type="text/javascript" src="../ewebeditor.js"></script>


<script type="text/javascript">

//第二步：创建一个编辑器实例，由于本例只用于上传接口，所以此处是隐藏的。如此页已经有了可以不用创建，使用现成的即可。
//以下"coolblue",值可以依据实际需要修改为您的样式名,通过此样式的后台设置来达到控制允许上传文件类型及文件大小
EWEBEDITOR.Create("eWebEditor1", {style:"coolblue",display:"none",width:"0",height:"0"});


//第三步：调用接口方法打开上传对话框
/*
openUploadDialog 方法
参数说明： 
type: 上传文件类型，可用值为"image","flash","media","file"
	image: 图片
	media: 媒体
	flash: Flash
	file: 附件

mode: 上传接口对话框模式
	0:只有常规模式
	1:常规模式+批量上传(单文件模式)					//单文件模式：1次只允许1个文件上传
	2:常规模式+批量上传(多文件模式)(默认)			//多文件模式：1次允许多个文件上传
	3:只有批量上传(单文件模式)
	4:只有批量上传(多文件模式)
	5:批量上传(单文件模式)+常规模式
	6:批量上传(多文件模式)+常规模式

savepathfilename : 文件上传后，用于接收上传文件路径文件名的表单名，返回包含路径的文件名
savefilename     : 返回上传文件的文件名
originalfilename : 返回原文件名

returnflag : 返回值方式标志
	1: 输入框始终只有最后一次上传的文件名
	2: (默认)支持多个文件，多个上传或多次操作后，输入框中保留多个文件，多个文件如“|”分隔。
*/


function DoClickUpload(s_Flag){
	var editor = EWEBEDITOR.Instances["eWebEditor1"];
	if (!editor){
		return;
	}

	switch(s_Flag){
	case 'image':
		editor.openUploadDialog({
			type : 'image',
			mode : '2',
			savepathfilename : 'd_image_savepath',
			savefilename : 'd_image_savefile',
			originalfilename : 'd_image_original',
			returnflag : '2'
		});
		break;
	case 'flash':
		editor.openUploadDialog({
			type : 'flash',
			mode : '2',
			savepathfilename : 'd_flash_savepath',
			savefilename : 'd_flash_savefile',
			originalfilename : 'd_flash_original',
			returnflag : '2'
		});
		break;
	case 'media':
		editor.openUploadDialog({
			type : 'media',
			mode : '2',
			savepathfilename : 'd_media_savepath',
			savefilename : 'd_media_savefile',
			originalfilename : 'd_media_original',
			returnflag : '2'
		});
		break;
	case 'file':
		editor.openUploadDialog({
			type : 'file',
			mode : '2',
			savepathfilename : 'd_file_savepath',
			savefilename : 'd_file_savefile',
			originalfilename : 'd_file_original',
			returnflag : '2'
		});
		break;
	}

}

</script>


<form method="post" name="myform" action="">

1. 此示例允许上传图片类型文件：<br>
上传文件：<input type="text" id="d_image_savepath" size="50"> 
保存文件名：<input type="text" id="d_image_savefile" size="20"> 
源文件名：<input type="text" id="d_image_original" size="20"> 
<input type="button" value="上传图片..." onclick="DoClickUpload('image')">
<br><br>

2. 此示例允许上传Flash类型文件：<br>
上传文件：<input type="text" id="d_flash_savepath" size="50"> 
保存文件名：<input type="text" id="d_flash_savefile" size="20"> 
源文件名：<input type="text" id="d_flash_original" size="20"> 
<input type="button" value="上传Flash..." onclick="DoClickUpload('flash')">
<br><br>

3. 此示例允许上传媒体类型文件：<br>
上传文件：<input type="text" id="d_media_savepath" size="50"> 
保存文件名：<input type="text" id="d_media_savefile" size="20"> 
源文件名：<input type="text" id="d_media_original" size="20"> 
<input type="button" value="上传媒体..." onclick="DoClickUpload('media')">
<br><br>

4. 此示例允许上传附件类型文件：<br>
上传文件：<input type="text" id="d_file_savepath" size="50"> 
保存文件名：<input type="text" id="d_file_savefile" size="20"> 
源文件名：<input type="text" id="d_file_original" size="20"> 
<input type="button" value="上传文件..." onclick="DoClickUpload('file')">
<br><br>

<input type=button value="查看源文件" onclick="location.replace('view-source:'+location)"> 

</form>

</body>
</html>
