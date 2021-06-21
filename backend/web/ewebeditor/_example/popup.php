<?php header("content-Type: text/html; charset=utf-8");?>
<HTML>
<HEAD>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<TITLE>eWebEditor ： 弹窗调用示例</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='example.css'>
</HEAD>
<BODY>

<p><b>导航 ： <a href="default.php">示例首页</a> &gt; 弹窗调用示例</b></p>
<p>当页面中表单的元素较多时，此方法可以使页面更加整洁，并加快表单页的加载速度。</p>
<p>点击“HTML编辑”按钮，在弹出窗口编辑一些内容，然后点“保存返回”按钮，看一下效果。</p>


<script type="text/javascript">
function eWebEditorPopUp(style, field, width, height) {
	window.open("../popup.htm?style="+style+"&link="+field, "", "width="+width+",height="+height+",toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no");
}
</script>


<FORM ACTION="retrieve.php" METHOD="post" NAME="myform">
<TABLE border="0" cellpadding="2" cellspacing="1">
<TR>
	<TD>编辑内容：</TD>
	<TD>
		<TEXTAREA NAME="content1" COLS="50" ROWS="10" style="width:550px">&lt;i&gt;弹窗调用示例&lt;/i&gt;</TEXTAREA><br>
		<INPUT TYPE="BUTTON" NAME="btn" VALUE="HTML编辑" ONCLICK="eWebEditorPopUp('popup', 'content1', 580, 380)">
	</TD>
</TR>
<TR>
	<TD align=right colspan=2>
	<INPUT type=submit value="提交">
	<INPUT type=reset value="重填">
	<INPUT type=button value="查看源文件" onclick="location.replace('view-source:'+location)">
	</TD>
</TR>
</TABLE>
</FORM>


</BODY>
</HTML>
