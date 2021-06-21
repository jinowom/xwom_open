<?php header("content-Type: text/html; charset=utf-8");
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

InitParam();

$GLOBALS["sAction"] = strtoupper(TrimGet("action"));
if ($GLOBALS["sAction"]=="FILE"){
	OutData("browsefile", GetFileList());
}else{
	$GLOBALS["sAction"] = "FOLDER";
	OutData("browsefolder", GetFolderList());
}



function GetFileList(){
	$s_ReturnFlag = GetSafeStr(TrimGet("returnflag"));
	$s_FolderType = GetSafeStr(TrimGet("foldertype"));
	$s_Dir = GetSafeStr(TrimGet("dir"));
	$s_CurrDir = "";

	$s_Dir = UTF8_to_Pagecode($s_Dir, true);

	switch ($s_FolderType){
	case "upload":
		$s_CurrDir = $GLOBALS["sUploadDir"];
		break;
	case "shareimage":
		$GLOBALS["sAllowExt"] = "";
		$s_CurrDir = $GLOBALS["sPathShareImage"];
		break;
	case "shareflash":
		$GLOBALS["sAllowExt"] = "";
		$s_CurrDir = $GLOBALS["sPathShareFlash"];
		break;
	case "sharemedia":
		$GLOBALS["sAllowExt"] = "";
		$s_CurrDir = $GLOBALS["sPathShareMedia"];
		break;
	default:
		$s_FolderType = "shareother";
		$GLOBALS["sAllowExt"] = "";
		$s_CurrDir = $GLOBALS["sPathShareOther"];
		break;
	}

	$s_Dir = str_replace("\\", "/", $s_Dir);
	if ((substr($s_Dir,0,1)=="/") || (substr($s_Dir,0,1)==".") || (substr($s_Dir, -1)==".") || (strpos($s_Dir, "./")!==false) || (strpos($s_Dir, "/.")!==false) || (strpos($s_Dir, "//")!==false) || (strpos($s_Dir, "..")!==false)){
		$s_Dir = "";
	}

	if ($s_Dir != "") {
		$ss_Dir = $s_CurrDir.Syscode2Pagecode($s_Dir,false);
		if (is_dir($ss_Dir)) {
			$s_CurrDir = $ss_Dir;
		}else{
			$s_Dir = "";
		}
	}

	$s_List = "";
	if (is_dir($s_CurrDir)){
		if ($handle = opendir($s_CurrDir)) {
			while (false !== ($file = readdir($handle))) {
				$sFileType = filetype($s_CurrDir.$file);
				if ($sFileType=="file"){
					$oFiles[] = $file;
				}
			}
		}

		$i = -1;
		if (isset($oFiles)){
			foreach( $oFiles as $oFile){
				if (CheckValidExt($oFile)) {
					$i = $i + 1;
					$sFileName = $s_CurrDir.$oFile;
					if ($i>0){
						$s_List = $s_List."||";
					}
					$s_List = $s_List.Syscode2Pagecode($oFile,true)."|".GetSizeUnit(filesize($sFileName))."|".date("Y-m-d H:i:s", filemtime($sFileName));
				}
			}
		}
	}
	if ($s_List==""){
		$s_List = "0";
	}

	$s_List = $s_ReturnFlag."|||".$s_FolderType."|||".$s_Dir."|||".$s_List;
	return $s_List;
}

function GetFolderList(){
	$s_ArrUpload="";
	$s_ArrShareImage="";
	$s_ArrShareFlash="";
	$s_ArrShareMedia="";
	$s_ArrShareOther="";
	
	$GLOBALS["nTreeIndex"] = 0;
	$s_ArrUpload = GetFolderTree($GLOBALS["sUploadDir"], "Upload", 1);

	$GLOBALS["sAllowExt"] = "";
	switch($GLOBALS["sType"]){
	case "FILE":
		$GLOBALS["nTreeIndex"] = 0;
		$s_ArrShareImage = GetFolderTree($GLOBALS["sPathShareImage"], "ShareImage", 1);
		$GLOBALS["nTreeIndex"] = 0;
		$s_ArrShareFlash = GetFolderTree($GLOBALS["sPathShareFlash"], "ShareFlash", 1);
		$GLOBALS["nTreeIndex"] = 0;
		$s_ArrShareMedia = GetFolderTree($GLOBALS["sPathShareMedia"], "ShareMedia", 1);
		$GLOBALS["nTreeIndex"] = 0;
		$s_ArrShareOther = GetFolderTree($GLOBALS["sPathShareOther"], "ShareOther", 1);
		break;
	case "MEDIA":
		$GLOBALS["nTreeIndex"] = 0;
		$s_ArrShareImage = "0";
		$s_ArrShareFlash = "0";
		$s_ArrShareMedia = GetFolderTree($GLOBALS["sPathShareMedia"], "ShareMedia", 1);
		$s_ArrShareOther = "0";
		break;
	case "FLASH":
		$GLOBALS["nTreeIndex"] = 0;
		$s_ArrShareImage = "0";
		$s_ArrShareFlash = GetFolderTree($GLOBALS["sPathShareFlash"], "ShareFlash", 1);
		$s_ArrShareMedia = "0";
		$s_ArrShareOther = "0";
		break;
	default:
		$GLOBALS["nTreeIndex"] = 0;
		$s_ArrShareImage = GetFolderTree($GLOBALS["sPathShareImage"], "ShareImage", 1);
		$s_ArrShareFlash = "0";
		$s_ArrShareMedia = "0";
		$s_ArrShareOther = "0";
		break;
	}

	$s_List = $s_ArrUpload . "|||" . $s_ArrShareImage . "|||" . $s_ArrShareFlash . "|||" . $s_ArrShareMedia . "|||" . $s_ArrShareOther;
	return $s_List;
}

function GetFolderTree($s_Dir, $s_Flag, $n_Indent){
	if ($handle = opendir($s_Dir)) {
		while (false !== ($file = readdir($handle))) {
			$sFileType = filetype($s_Dir.$file);
			if ($sFileType=="dir"){
				if (($file!=".")&&($file!="..")){
					$oDirs[] = $file;
				}
			}
		}
	}

	$s_List = "";
	if (isset($oDirs)){
		$i = 0;
		$n_Count = count($oDirs);

		foreach( $oDirs as $oDir){
			$i = $i + 1;
			if ($i < $n_Count) {
				$s_LastFlag = "0";
			}else{
				$s_LastFlag = "1";
			}
			if ($GLOBALS["nTreeIndex"]>0){
				$s_List = $s_List."||";
			}
			$s_List = $s_List.Syscode2Pagecode($oDir,true)."|".$n_Indent."|".$s_LastFlag;
			$GLOBALS["nTreeIndex"] = $GLOBALS["nTreeIndex"] + 1;
			$s_List = $s_List.GetFolderTree($s_Dir.$oDir."/", $s_Flag, $n_Indent+1);
		}
	}
	if ($s_List==""){
		$s_List="0";
	}

	return $s_List;
}

function OutError($s_ErrCode){
	OutData("browseerr", $s_ErrCode);
}

function OutData($s_Action, $s_Data){
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

	OutScript($s_Action, $s_FormItem, $s_Script);
}

function OutScript($s_Action, $s_FormItem, $s_Script){
	$s_Form1 = "";
	$s_Form2 = "";

	if ($GLOBALS["sWSRootUrl"] != ""){
		$s_Form1 = "<form name=\"myform\" action=\"" . $GLOBALS["sWSRootUrl"] . "php/fs.php?act=" . $s_Action . "\" method=\"post\" target=\"_self\">";
		$s_Form2 = "</form>";
		$s_Script = "document.myform.submit();";
	}

	echo "<html><head><title>eWebEditor</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n".
		"<script type=\"text/javascript\">\r\n".
		"window.onload = _Onload; var bRun = false; function _Onload(){if(bRun){return;}; bRun = true; " . $s_Script . "}\r\n".
		"</script>\r\n".
		"</head><body>\r\n" . $s_Form1 . "\r\n" . $s_FormItem . "\r\n" . $s_Form2 . "\r\n</body></html>";
	exit;
}

function CheckValidExt($s_FileName){
	if($GLOBALS["sAllowExt"] == ""){
		return true;
	}
	preg_match("/\.([a-zA-Z0-9]{2,4})$/",$s_FileName,$exts);
	$sExt = $exts[1];
	$aExt = explode('|',$GLOBALS["sAllowExt"]);
	if(!in_array(strtoupper($sExt),$aExt)){
		return false;
	}
	return true;
}

function  InitParam(){
	global $sType, $sStyleName, $sCusDir, $sAction;
	global $nTreeIndex;
	global $sAllowExt, $sUploadDir, $sBaseUrl, $sContentPath, $nAllowBrowse;
	global $sPathShareImage, $sPathShareFlash, $sPathShareMedia, $sPathShareOther;
	global $sWSRootUrl;

	$sWSRootUrl = GetSafeUrl(DeCode9193(TrimGet("ws")));
	$sType = GetSafeStr(strtoupper(TrimGet("type")));
	$sStyleName = GetSafeStr(TrimGet("style"));
	$sCusDir = GetSafeStr(TrimGet("cusdir"));

	$s_SKey = GetSafeStr(TrimGet("skey"));

	$bValidStyle = false;
	$numElements = count($GLOBALS["aStyle"]);
	for($i=1; $i<=$numElements; $i++){
		$aStyleConfig = explode("|||", $GLOBALS["aStyle"][$i]);
		if (strtolower($sStyleName)==strtolower($aStyleConfig[0])){
			$bValidStyle = true;
			break;
		}
	}

	if ($bValidStyle == false) {
		OutError("style");
		return;
	}

	if ($sWSRootUrl!="" && $aStyleConfig[112]==""){
		OutError("ws");
		exit;
	}

	if ($aStyleConfig[61] != "1"){
		$sCusDir = "";
	}

	if (($aStyleConfig[61] == "2") && ($s_SKey != "")){
		$ss_FileSize = GetSAPIvalue($s_SKey, "FileSize");
		$ss_FileBrowse = GetSAPIvalue($s_SKey, "FileBrowse");
		$ss_SpaceSize = GetSAPIvalue($s_SKey, "SpaceSize");
		$ss_SpacePath = GetSAPIvalue($s_SKey, "SpacePath");
		$ss_PathMode = GetSAPIvalue($s_SKey, "PathMode");
		$ss_PathUpload = GetSAPIvalue($s_SKey, "PathUpload");
		$ss_PathCusDir = GetSAPIvalue($s_SKey, "PathCusDir");
		$ss_PathCode = GetSAPIvalue($s_SKey, "PathCode");
		$ss_PathView = GetSAPIvalue($s_SKey, "PathView");
		
		if (is_numeric($ss_FileSize)){
			$aStyleConfig[11] = $ss_FileSize;
			$aStyleConfig[12] = $ss_FileSize;
			$aStyleConfig[13] = $ss_FileSize;
			$aStyleConfig[14] = $ss_FileSize;
			$aStyleConfig[15] = $ss_FileSize;
			$aStyleConfig[45] = $ss_FileSize;
		}else{
			$ss_FileSize = "";
		}
		if ($ss_FileBrowse == "0" || $ss_FileBrowse == "1"){
			$aStyleConfig[43] = $ss_FileBrowse;
		}else{
			$ss_FileBrowse = "";
		}
		if (is_numeric($ss_SpaceSize)){
			$aStyleConfig[78] = $ss_SpaceSize;
		}else{
			$ss_SpaceSize = "";
		}
		if ($ss_PathMode != ""){
			$aStyleConfig[19] = $ss_PathMode;
		}
		if ($ss_PathUpload != ""){
			$aStyleConfig[3] = $ss_PathUpload;
		}
		if ($ss_PathCode != ""){
			$aStyleConfig[23] = $ss_PathCode;
		}
		if ($ss_PathView != ""){
			$aStyleConfig[22] = $ss_PathView;
		}
		
		$sCusDir = $ss_PathCusDir;
	}


	$sBaseUrl = $aStyleConfig[19];
	$nAllowBrowse = (int)$aStyleConfig[43];

	if($nAllowBrowse!=1){
		OutError("notallow");
		return;
	}

	if ($sCusDir!=""){
		$sCusDir = str_replace("\\", "/", $sCusDir);
		if ((substr($sCusDir,0,1)=="/") || (substr($sCusDir,0,1)==".") || (substr($sCusDir, -1)==".") || (strpos($sCusDir, "./")!==false) || (strpos($sCusDir, "/.")!==false) || (strpos($sCusDir, "//")!==false) || (strpos($sCusDir, "..")!==false)){
			$sCusDir = "";
		}else{
			if (substr($sCusDir, -1) != "/"){
				$sCusDir = $sCusDir."/";
			}
		}
	}

	$sUploadDir = $aStyleConfig[3];
	if ($sBaseUrl!="3"){
		if (substr($sUploadDir, 0, 1) != "/"){
			$sUploadDir = "../".$sUploadDir;
		}
		$sUploadDir = realpath($sUploadDir);
	}
	$sUploadDir = GetSlashPath($sUploadDir);
	$sUploadDir = $sUploadDir.$sCusDir;

	switch ($sType){
	case "FILE":
		$sAllowExt = $aStyleConfig[6];
		break;
	case "MEDIA":
		$sAllowExt = $aStyleConfig[9];
		break;
	case "FLASH":
		$sAllowExt = $aStyleConfig[7];
		break;
	default:
		$sAllowExt = $aStyleConfig[8];
		break;
	}
	$sAllowExt = strtoupper($sAllowExt);

	$sPathShareImage = GetSlashPath(realpath("../sharefile/image/"));
	$sPathShareFlash = GetSlashPath(realpath("../sharefile/flash/"));
	$sPathShareMedia = GetSlashPath(realpath("../sharefile/media/"));
	$sPathShareOther = GetSlashPath(realpath("../sharefile/other/"));

}

function GetSlashPath($p){
	if ((substr($p,-1)!="\\") && (substr($p,-1)!="/")){
		return $p."/";
	}
	return $p;
}


function GetSizeUnit($n_Size){
	return number_format(($n_Size / 1024), 2, ".", "") . "K";
}

?>