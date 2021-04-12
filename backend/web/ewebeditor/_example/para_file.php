<?php header("content-Type: text/html; charset=utf-8");?>
<HTML>
<HEAD>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<TITLE>eWebEditor ： 上传文件接口示例</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='example.css'>
</HEAD>
<BODY>

<p><b>导航 ： <a href="default.php">示例首页</a> &gt; 上传文件接口示例</b></p>
<p>通过使用这个接口功能，您可以获取到所有通过编辑器上传的图片或文件的文件名及路径。</p>
<p>在编辑器中上传一个文件或图片，看一下效果。</p>


<Script Language=JavaScript>
function doChange(objText, objDrop){
	if (!objDrop) return;
	var str = objText.value;
	var arr = str.split("|");
	objDrop.length=0;
	for (var i=0; i<arr.length; i++){
		objDrop.options[i] = new Option(arr[i], arr[i]);
	}
}
</Script>


<FORM method="post" name="myform" action="retrieve.php">
<TABLE border="0" cellpadding="2" cellspacing="1">
<TR>
	<TD>编辑内容：</TD>
	<TD>
		<INPUT type="hidden" name="content1" value="">
		<IFRAME ID="eWebEditor1" src="../ewebeditor.htm?id=content1&style=coolblue&originalfilename=myText1&savefilename=myText2&savepathfilename=myText3" frameborder="0" scrolling="no" width="550" height="350"></IFRAME>
	</TD>
</TR>
<TR>
	<TD>参数：originalfilename</TD>
	<TD><input type=text id=myText1 style="width:200px" onchange="doChange(this,myDrop1)">&nbsp;<select id=myDrop1 size=1 style="width:200px"></select></TD>
</TR>
<TR>
	<TD>参数：savefilename</TD>
	<TD><input type=text id=myText2 style="width:200px" onchange="doChange(this,myDrop2)">&nbsp;<select id=myDrop2 size=1 style="width:200px"></select></TD>
</TR>
<TR>
	<TD>参数：savepathfilename</TD>
	<TD><input type=text id=myText3 style="width:200px" onchange="doChange(this,myDrop3)">&nbsp;<select id=myDrop3 size=1 style="width:350px"></select></TD>
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
