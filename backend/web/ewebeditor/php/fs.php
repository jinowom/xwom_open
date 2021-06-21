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


DoAction();


function DoAction(){
	$s_Action = TrimGet("act");

	$s_FormItem = "";
	$s_Script = "";
	switch($s_Action){
	case "uploadsave":
		$s_Ori = TrimPost("d_ori");
		$s_Save = TrimPost("d_save");
		$s_Thumb = TrimPost("d_thumb");
		$s_FormItem = GetHideInputHtml("d_ori", $s_Ori) . GetHideInputHtml("d_save", $s_Save) . GetHideInputHtml("d_thumb", $s_Thumb);
		$s_Script = "parent.UploadSaved(document.getElementById('d_ori').value, document.getElementById('d_save').value, document.getElementById('d_thumb').value);";
		break;
	case "uploadremote":
		$s_Content = TrimPost("d_content");
		$s_Ori = TrimPost("d_ori");
		$s_Save = TrimPost("d_save");
		$s_FormItem = GetHideInputHtml("d_content", $s_Content) . GetHideInputHtml("d_ori", $s_Ori) . GetHideInputHtml("d_save", $s_Save);
		$s_Script = "parent.setHTML(document.getElementById('d_content').value); ".
			"try{parent.addUploadFiles(document.getElementById('d_ori').value, document.getElementById('d_save').value);} catch(e){} ".
			"parent.remoteUploadOK();";
		break;
	case "uploaderr":
		$s_ErrCode = TrimPost("d_errcode");
		$s_FormItem = GetHideInputHtml("d_errcode", $s_ErrCode);
		$s_Script = "parent.UploadError(document.getElementById('d_errcode').value);";
		break;
	default:
		$s_Data = TrimPost("d_data");
		$s_FormItem = GetHideInputHtml("d_data", $s_Data);
		$s_Script = "var d=document.getElementById(\"d_data\").value; ";

		switch($s_Action){
		case "browsefile":
			$s_Script = $s_Script . "parent.setFileList(d);";
			break;
		case "browsefolder":
			$s_Script = $s_Script . "parent.setFolderList(d);";
			break;
		case "browseerr":
			$s_Script = $s_Script . "parent.ServerError(d);";
			break;
		}

	}

	OutScript($s_FormItem, $s_Script);
}

function OutScript($s_FormItem, $s_Script){
	header("X-XSS-Protection:0");
	echo "<html><head><title>eWebEditor</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n".
		"<script type=\"text/javascript\">\r\n".
		"window.onload = _Onload; var bRun = false; function _Onload(){if(bRun){return;}; bRun = true; " . $s_Script . "}\r\n".
		"</script>\r\n".
		"</head><body>\r\n" . $s_FormItem . "\r\n</body></html>";
	exit;
}

?>