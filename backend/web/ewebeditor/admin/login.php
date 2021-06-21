<?php
header("Content-Type: text/html;charset=utf-8");

/*
*######################################
* eWebEditor V12.1 - Advanced online web based WYSIWYG HTML editor.
* Copyright (c) 2003-2020 eWebSoft.com
*
* For further information go to http://www.ewebeditor.net/
* This copyright notice MUST stay intact for use.
*######################################
*/


@session_start();

require("../php/config.php");

$sAction = strtoupper(TrimGet("action"));

switch ($sAction){
case "LOGIN":
	$s_Usr = TrimGet("usr");
	$s_Pwd = TrimGet("pwd");
	$s_Host = TrimGet("h");
	if (($s_Usr == $sUsername) && ($s_Pwd == $sPassword) && (strlen($s_Usr)>=8) && (strlen($s_Pwd)>=8)){
		$_SESSION["eWebEditor_User"] = "OK";
		$_SESSION["eWebEditor_Host"] = $s_Host;
		header("Location:default.php");
		exit;
	}
	break;
case "OUT":
	$_SESSION["eWebEditor_User"] = "";
	break;
}



function TrimGet($name){
	if (isset($_GET[$name])){
		return trim($_GET[$name]);
	}
	if (isset($_POST[$name])){
		return trim($_POST[$name]);
	}
	return "";
}

?>

<HTML>
<HEAD>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<TITLE>eWebEditor在线编辑器 - 后台管理</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<style>
body,td,a,p,input{font-size:9pt}
body {margin:0px;background-color:#d9ddf7}
.c92 {FONT-SIZE: 9pt; COLOR: #003366; LINE-HEIGHT: 150%}
A:hover {COLOR: #ff9900}
A:link {COLOR: #003366}
.input {BORDER-RIGHT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-BOTTOM: #000000 1px solid;width:110px;height:18px;}
</style>
<script type="text/javascript" src="private.js"></script>
<script type="text/javascript">
function GetHost(){
	var s_Host = location.hostname;
	var n = s_Host.indexOf(":");
	if (n>0){
		n = s_Host.indexOf("]");
		if (n<0){
			s_Host = "[" + s_Host + "]";
		}
	}
	return EWEBPunycode.EncodeDomain(s_Host);
}

function checkForm(){
	var frm = document.loginform
	if(frm.usr.value == ""){
		alert('用户名不允许为空');
		frm.usr.focus();
		return false;
	}
	if(frm.pwd.value == ""){
		alert('用户密码不允许为空');
		frm.pwd.focus();
		return false;
	}
	frm.h.value = GetHost();
	frm.submit()
}

document.onkeydown = function(ev){
	ev = ev || window.event;
	var n_KeyCode = ev.keyCode || ev.which;
	if(n_KeyCode==13){
		return checkForm();
	}
	return true;
}
</script>

</head>
<BODY onload=document.loginform.usr.focus()>
<BR><BR>
<TABLE cellSpacing=0 cellPadding=0 width=500 align=center border=0>
  <TBODY>
  <TR>
    <TD height=60></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 width=732 align=center border=0>
  <TBODY>
  <TR>
    <TD colSpan=7><IMG height=1 alt="" src="images/spacer.gif" width=718></TD>
    <TD rowSpan=6>&nbsp; </TD>
    <TD><IMG height=1 alt="" src="images/spacer.gif" width=1></TD></TR>
  <TR>
    <TD vAlign=bottom colSpan=3 rowSpan=2><IMG height=201 alt="" src="images/2_10.gif" width=341></TD>
    <TD vAlign=bottom colSpan=2><IMG height=108 alt="" src="images/2_11.gif" width=295></TD>
    <TD colSpan=2>&nbsp; </TD>
    <TD><IMG height=110 alt="" src="images/spacer.gif" width=1></TD></TR>
  <TR>
    <TD background=images/1_12.gif colSpan=4>
      <TABLE cellSpacing=0 cellPadding=3 width="50%" border=0>
        <FORM name=loginform action="?action=login" method=post>
		<input type=hidden name=h>
        <TBODY>
        <TR>
          <TD class=c92 width="24%">用户名</TD>
          <TD width="76%"><INPUT class=input size=16 name=usr> </TD></TR>
        <TR>
          <TD class=c92 width="24%">密　码</TD>
          <TD width="76%"><INPUT class=input type=password size=16 name=pwd> </TD></TR>
        <TR>
          <TD width="24%">&nbsp;</TD>
          <TD width="76%">
            <DIV align=left>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<IMG style="CURSOR: hand" onclick="return checkForm()" src="images/login.gif" border=0> 
        </DIV></TD></TR></FORM></TBODY></TABLE></TD>
    <TD><IMG height=93 alt="" src="images/spacer.gif" width=1></TD></TR>
  <TR>
    <TD width=254 background=images/1_13.gif colSpan=2 height=161><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD height=50></TD></TR>
        <TR>
          <TD align=right></TD></TR>
        <TR>
          <TD height=20></TD></TR>
        <TR>
          <TD align=right></TD></TR></TBODY></TABLE></TD>
    <TD width=387 background=images/1_14.gif colSpan=4 height=212 
    rowSpan=2></TD>
    <TD rowSpan=3><IMG height=242 alt="" src="images/spacer.gif" width=77></TD>
    <TD><IMG height=161 alt="" src="images/spacer.gif" width=1></TD></TR>
  <TR>
    <TD colSpan=2 rowSpan=2><IMG height=81 alt="" src="images/spacer.gif" width=254></TD>
    <TD><IMG height=51 alt="" src="images/spacer.gif" width=1></TD></TR>
  <TR>
    <TD colSpan=4><IMG height=30 alt="" src="images/spacer.gif" width=387></TD>
    <TD><IMG height=30 alt="" src="images/spacer.gif" width=1></TD></TR>
  <TR>
    <TD><IMG height=1 alt="" src="images/spacer.gif" width=195></TD>
    <TD><IMG height=1 alt="" src="images/spacer.gif" width=59></TD>
    <TD><IMG height=1 alt="" src="images/spacer.gif" width=87></TD>
    <TD><IMG height=1 alt="" src="images/spacer.gif" width=214></TD>
    <TD><IMG height=1 alt="" src="images/spacer.gif" width=81></TD>
    <TD><IMG height=1 alt="" src="images/spacer.gif" width=5></TD>
    <TD><IMG height=1 alt="" src="images/spacer.gif" width=77></TD>
    <TD><IMG height=1 alt="" src="images/spacer.gif" width=14></TD>
    <TD></TD></TR></TBODY></TABLE>
</BODY></HTML>
