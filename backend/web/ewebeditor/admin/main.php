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

$sPosition = $sPosition."后台管理首页";

eWebEditor_Header();
eWebEditor_Content();
eWebEditor_Footer();


function eWebEditor_Content(){
	?>

	<table border=0 cellspacing=1 align=center class=navi>
	<tr><th><?php echo $GLOBALS["sPosition"]?></th></tr>
	</table>

	<br>

	<table border=0 cellspacing=1 align=center class=list>
	<tr><th colspan=2>eWebEditor 12.1 版权、常用联系方法、技术支持</th></tr>
	<tr>
		<td width="15%">软件版本：</td>
		<td width="85%">eWebEditor Version 12.1 for PHP 多语言商业版</td>
	</tr>
	<tr>
		<td width="15%">版权所有：</td>
		<td width="85%"><a href="http://www.ewebsoft.com" target="_blank">eWebSoft.com</a>&nbsp;&nbsp;已获得国家版权局颁发的《计算机软件著作权登记证书》,登记号:2004SR06549</td>
	</tr>
	<tr>
		<td width="15%">主页地址：</td>
		<td width="85%"><a href="http://www.ewebeditor.net/" target="_blank">eWebEditor中文站(www.eWebEditor.net)</a>&nbsp;&nbsp;&nbsp;<a href="http://service.ewebeditor.net/" target="_blank">客服中心(service.eWebEditor.net)</a></td>
	</tr>
	<tr>
		<td width="15%">授权验证：</td>
		<td width="85%">
		<?php
		if ($GLOBALS["sLicense"]==""){
			echo "<span class=red>未授权</span> [<a href='modilicense.php'>设置授权序列号</a>]";
		}else{
		?>
			<iframe name=ifrLicense src='' style='width:100%;height:20px;margin:0' scrolling='no' frameborder=0></iframe>
			<div style="display:none;position:absolute;">
			<form name=formLicense action="http://service.ewebeditor.net/i_license.asp" target="ifrLicense" method=post>
			<input type=hidden name="d_license" value="<?php echo $GLOBALS["sLicense"]?>">
			<input type=hidden name="d_url" value="">
			<input type=hidden name="d_version" value="12.1">
			</form>
			</div>

			<script type="text/javascript">
			submitLicense();
			</script>
		
		<?php
		}
		?>
		
		</td>
	</tr>
	</table>

	<br>

	<table border=0 cellspacing=1 align=center class=list>
	<tr><th colspan=2>服务器的有关参数</th><th colspan=2>组件支持有关参数</th></tr>
	<tr>
		<td width="15%">服务器名：</td>
		<td width="45%"><?php echo $_SERVER["SERVER_NAME"]?></td>
		<td width="20%">mysql数据库：</td>
		<td width="20%"><?php echo showResult(function_exists("mysql_close"))?></td>
	</tr>
	<tr>
		<td width="15%">服务器IP：</td>
		<td width="45%"><?php echo (isset($_SERVER["LOCAL_ADDR"])?$_SERVER["LOCAL_ADDR"]:$_SERVER["SERVER_ADDR"])?></td>
		<td width="20%">odbc数据库：</td>
		<td width="20%"><?php echo showResult(function_exists("odbc_close"))?></td>
	</tr>
	<tr>
		<td width="15%">服务器端口：</td>
		<td width="45%"><?php echo $_SERVER["SERVER_PORT"]?></td>
		<td width="20%">SQL Server数据库：</td>
		<td width="20%"><?php echo showResult(function_exists("mssql_close"))?></td>
	</tr>
	<tr>
		<td width="15%">服务器时间：</td>
		<td width="45%"><?php echo date("Y年m月d日H点i分s秒")?></td>
		<td width="20%">msql数据库：</td>
		<td width="20%"><?php echo showResult(function_exists("msql_close"))?></td>
	</tr>
	<tr>
		<td width="15%">PHP版本：</td>
		<td width="45%"><?php echo PHP_VERSION?></td>
		<td width="20%">MAIL：</td>
		<td width="20%"><?php echo showResult(function_exists("mail"))?></td>
	</tr>
	<tr>
		<td width="15%">WEB服务器版本：</td>
		<td width="45%"><?php echo $_SERVER["SERVER_SOFTWARE"]?></td>
		<td width="20%">图形处理 GD Library：</td>
		<td width="20%"><?php echo showResult(function_exists("imageline"))?></td>
	</tr>

	<tr>
		<td width="15%">服务器操作系统：</td>
		<td width="45%"><?php echo PHP_OS?></td>
		<td width="20%">FTP：</td>
		<td width="20%"><?php echo showResult(function_exists("ftp_login"))?></td>
	</tr>
	<tr>
		<td width="15%">脚本超时时间：</td>
		<td width="45%"><?php echo get_cfg_var("max_execution_time")?> 秒</td>
		<td width="20%">iconv 函数：</td>
		<td width="20%"><?php echo showResult(function_exists("iconv"))?></td>
	</tr>
	<tr>
		<td width="15%">站点物理路径：</td>
		<td width="45%"><?php echo Syscode2Pagecode(realpath("../"),true)?></td>
		<td width="20%">mbstring 函数：</td>
		<td width="20%"><?php echo showResult(function_exists("mb_substr"))?></td>
	</tr>
	<tr>
		<td width="15%">脚本上传文件大小限制：</td>
		<td width="45%"><?php echo get_cfg_var("upload_max_filesize")?get_cfg_var("upload_max_filesize"):"不允许上传附件"?></td>
		<td width="20%">显示错误信息：</td>
		<td width="20%"><?php echo showResult(get_cfg_var("display_errors"))?></td>
	</tr>
	<tr>
		<td width="15%">POST提交内容限制：</td>
		<td width="45%"><?php echo get_cfg_var("post_max_size")?></td>
		<td width="20%">使用URL打开文件：</td>
		<td width="20%"><?php echo showResult(get_cfg_var("allow_url_fopen"))?></td>
	</tr>
	<tr>
		<td width="15%">服务器语种：</td>
		<td width="45%"><?php echo getenv("HTTP_ACCEPT_LANGUAGE")?></td>
		<td width="20%">压缩文件支持(Zlib)：</td>
		<td width="20%"><?php echo showResult(function_exists("gzclose"))?></td>
	</tr>
	<tr>
		<td width="15%">脚本运行时可占最大内存：</td>
		<td width="45%"><?php echo get_cfg_var("memory_limit")?get_cfg_var("memory_limit"):"无"?></td>
		<td width="20%">ZEND支持：</td>
		<td width="20%"><?php echo showResult(function_exists("zend_version"))?></td>
	</tr>	
	</table>

	
<?php
}



function showResult($v){
	if($v==1){
		echo'<b>√</b>&nbsp;<font class=gray>支持</font>';
	}else{
		echo'<font class=red><b>×</b></font>&nbsp;<font class=gray>不支持</font>';
	}
}

?>