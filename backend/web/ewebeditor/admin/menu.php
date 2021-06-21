<?php
/*
*######################################
* eWebEditor V12.1 - Advanced online web based WYSIWYG HTML editor.
* Copyright (c) 2003-2020 eWebSoft.com
*
* For further information go to http://www.ewebeditor.net/
* This copyright notice MUST stay intact for use.
*######################################
*/


require("private.php");

?>

<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>eWebEditor</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link type=text/css href='private.css' rel=stylesheet>
<base target=main>
</head>
<script language=javascript>
<!--
function menu_tree(meval)
{
  var left_n=eval(meval);
  if (left_n.style.display=="none")
  { eval(meval+".style.display='';"); }
  else
  { eval(meval+".style.display='none';"); }
}
-->
</script>
<body>
<center>

  <table cellspacing=0  class="Menu">
  <tr><th align=center  onclick="javascript:menu_tree('left_1');" >≡ 首选服务 ≡</th></tr>
  <tr id='left_1'><td >
    <table width='100%'>
    <tr><td><img border=0 src='images/menu.gif' align=absmiddle>&nbsp;<a href='style.php'>样式管理</a></td></tr>
    <tr><td><img border=0 src='images/menu.gif' align=absmiddle>&nbsp;<a href='upload.php'>上传管理</a></td></tr>
    </table>
  </td></tr>
  </table>

  <table width='90%' height=2><tr ><td></td></tr></table>
  <table cellspacing=0  class="Menu">
  <tr><th align=center  onclick="javascript:menu_tree('left_2');" >≡ 辅助服务 ≡</th></tr>
  <tr id='left_2'><td>
    <table width='100%'>
    <tr><td><img border=0 src='images/menu.gif' align=absmiddle>&nbsp;<a href='main.php'>后台首页</a></td></tr>
	<tr><td><img border=0 src='images/menu.gif' align=absmiddle>&nbsp;<a href='../_example/default.php' target='_blank'>示例首页</a></td></tr>
    <tr><td><img border=0 src='images/menu.gif' align=absmiddle>&nbsp;<a href='modipwd.php'>修改密码</a></td></tr>
	<tr><td><img border=0 src='images/menu.gif' align=absmiddle>&nbsp;<a href='modilicense.php'>序 列 号</a></td></tr>
    <tr><td><img border=0 src='images/menu.gif' align=absmiddle>&nbsp;<a onclick="return confirm('提示：您确定要退出系统吗？')" href='login.php?action=out' target='_parent'>退出后台</a></td></tr>
    </table>
  </td></tr>
  </table>
  
  <table width='90%' height=2><tr ><td></td></tr></table>
  <table cellspacing=0  class="Menu">
  <tr><th align=center  >〓 版本信息 〓</th></tr>
  <tr><td align=center>eWebEditor V12.1</td></tr>
  <tr><td align=center><a href='http://www.ewebeditor.net' target=_blank><b>在线帮助</b></a></td></tr>
  </table>

</center>
</body>
</html>