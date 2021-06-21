<?php header("content-Type: text/html; charset=utf-8");?>
<HTML>
<HEAD>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<TITLE>eWebEditor ： 远程文件自动上传示例</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='example.css'>
</HEAD>
<BODY>

<p><b>导航 ： <a href="default.php">示例首页</a> &gt; 远程文件自动上传示例</b></p>
<p>演示操作说明：</p>
<ul>
<li>编辑区中的图片地址为：http://www.ewebeditor.net/images/ewebeditor.gif
<li>点击按钮<img src="images/remoteupload.gif">，然后转到“代码”模式看一下，编辑区的图片的地址已经到本地服务器了。
<li>或点此表单的“提交”，提交后，用IE的“查看源文件”看一下，图片的地址也到本地服务器了。
<li>到eWebEditor所有的目录下的uploadfile目录中，查看一下，是不是多了一个图片文件，这个文件就是远程自动获取的。
</ul>


<script language=javascript>
// 表单提交检测
function doCheck(){
	// 取编辑器对象，然后可以调用对象接口
	var editor1 = document.getElementById("eWebEditor1").contentWindow;


	// 检测表单的有效性
	// 如：标题不能为空，内容不能为空，等等...
	if (editor1.getHTML()=="") {
		alert("内容不能为空！");
		return false;
	}

	// 表单有效性检测完后，自动上传远程文件
	// 函数： remoteUpload(strEventUploadAfter)
	// 参数：strEventUploadAfter ; 上传完后，触发的函数名，如果上传完后不需动作可不填参数
	editor1.remoteUpload("doSubmit()");
	return false;

}

// 表单提交（当远程上传完成后，触发此函数）
function doSubmit(){
	document.myform.submit();
}
</script>


<FORM method="post" name="myform" action="retrieve.php" onsubmit="return doCheck();">
<TABLE border="0" cellpadding="2" cellspacing="1">
<TR>
	<TD>编辑内容：</TD>
	<TD>
		<INPUT type="hidden" name="content1" value="&lt;IMG src=&quot;http://www.ewebeditor.net/images/ewebeditor.gif&quot;&gt;">
		<IFRAME ID="eWebEditor1" src="../ewebeditor.htm?id=content1&style=coolblue" frameborder="0" scrolling="no" width="550" height="350"></IFRAME>
	</TD>
</TR>
<TR>
	<TD colspan=2 align=right>
	<INPUT type=submit value="提交"> 
	<INPUT type=reset value="重填"> 
	<INPUT type=button value="查看源文件" onclick="location.replace('view-source:'+location)"> 
	</TD>
</TR>
</TABLE>
</FORM>



</BODY>
</HTML>
