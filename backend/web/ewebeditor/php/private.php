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
if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")){
@date_default_timezone_set(@date_default_timezone_get());
}
@session_start();
require("config.php");
function MD5_16($s){
return substr(md5($s),8,16);
}
function TrimGet($p){
if (isset($_GET[$p])){
return stripslashes_if_gpc_magic_quotes(trim($_GET[$p]));
}else{
return "";
}}
function TrimPost($p){
if (isset($_POST[$p])){
return stripslashes_if_gpc_magic_quotes(trim($_POST[$p]));
}else{
return "";
}}
function stripslashes_if_gpc_magic_quotes( $str ) {
if(get_magic_quotes_gpc()) {
return stripslashes($str);
} else {
return $str;
}}
function GetSAPIvalue($s_SessionKey, $s_ParamName){
$p="eWebEditor_" . $s_SessionKey . "_" . $s_ParamName;
if (isset($_SESSION[$p])){
return trim($_SESSION[$p]);
}else{
return "";
}}
function IsInt($str){
if ($str==""){
return false;
}
if (preg_match("/[^0-9]+/",$str)){
return false;
}else{
return true;
}}
function IsOkSParams($s_SParams, $s_EncryptKey){
if ($s_SParams == ""){return false;}
$n = strpos($s_SParams, "|");
if ($n === false) {return false;}
$s1 = substr($s_SParams, 0, $n);
$s2 = substr($s_SParams, $n + 1);
if (MD5_16($s_EncryptKey.$s2) != $s1){return false;}
return true;
}
function Syscode2Pagecode($str, $b){
$s_SysCode = "gbk";
$s_PageCode = "utf-8";
if (($s_SysCode!=$s_PageCode) && (function_exists("iconv"))){
if ($b){
return iconv($s_SysCode, $s_PageCode, $str);
}else{
return iconv($s_PageCode, $s_SysCode, $str);
}}else{
return $str;
}}
function UTF8_to_Pagecode($str, $b){
$s_UTF8 = "utf-"."8";
$s_PageCode = "utf-8";
if (($s_UTF8!=$s_PageCode) && (function_exists("iconv"))){
if ($b){
return iconv($s_UTF8, $s_PageCode, $str);
}else{
return iconv($s_PageCode, $s_UTF8, $str);
}}else{
return $str;
}	
}
function HTMLEncode($str){
return htmlspecialchars($str, ENT_COMPAT, "ISO-8859-1");
}
function GetHideInputHtml($s_Name, $s_Value){
return "<input type=\"hidden\" name=\"" . $s_Name . "\" id=\"" . $s_Name . "\" value=\"" . HTMLEncode($s_Value) . "\">";
}
function GetSafeStr($s){
if (preg_match("/[\<\>\"]+/", $s)){
return "";
}else{
return $s;
}}
function GetSafeUrl($s){
if (preg_match("/[\<\>\"\'\;\(\)\?\%\&\r\n\t]+/", $s)){
return "";
}else{
return $s;
}}
function DeCode9193($s){
$s_Ret = $s;
$s_Ret = str_replace(";91;", "[", $s_Ret);
$s_Ret = str_replace(";93;", "]", $s_Ret);
return $s_Ret;
}
?>