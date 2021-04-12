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
if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")){
@date_default_timezone_set(@date_default_timezone_get());
}
@session_start();
if(!isset($_SESSION["eWebEditor_User"]) || $_SESSION["eWebEditor_User"]!="OK"){
echo "<script language=javascript>top.location.href='login.php';</script>";
exit;
}
require("../php/config.php");
CheckAndUpdateConfig();
$sAction = strtoupper(TrimGet("action"));
$sPosition = "当前位置：";
function eWebEditor_Header(){
echo "<html><head><meta http-equiv='X-UA-Compatible' content='IE=EmulateIE7'>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>"
."<title>eWebEditor在线编辑器 - 后台管理</title>"
."<link rel='stylesheet' type='text/css' href='private.css'>"
."<script language='javascript' src='private.js'></script>";
echo "</head>";
echo "<body>";
echo "<a name=top></a>";
}
function eWebEditor_Footer(){
echo "<table border=0 cellpadding=0 cellspacing=0 align=center width='100%'>"
."<tr><td height=40></td></tr>"
."<tr><td><hr size=1 color=#000000 width='60%' align=center></td></tr>"
."<tr>"
."<td align=center>Copyright  &copy;  2003-2020  <b>eWebEditor<font color=#CC0000>.net</font></b> <b>eWebSoft<font color=#CC0000>.com</font></b>, All Rights Reserved .</td>"
."</tr>"
."<tr>"
."<td align=center><a href='mailto:service@ewebsoft.com'>service@ewebsoft.com</a></td>"
."</tr>"
."</table>";
echo "</body></html>";
}
function IsSafeStr($str){
$aBadstr = array("'", "|", "\"");
for ($i=0;$i<count($aBadstr);$i++){
if (strpos($str, $aBadstr[$i]) !== false) {
return false;
}}
return true;
}
function IsSafeUsrPwd($str){
$aBadstr = array("'", "\"", "<", ">", "(", ")", "[", "]", "{", "}", "\r", "\n", "\t");
for ($i=0;$i<count($aBadstr);$i++){
if (strpos($str, $aBadstr[$i]) !== false) {
return false;
}}
return true;
}
function GoError($str){
echo "<script language=javascript>alert('".$str."\\n\\n系统将自动返回前一页面...');history.back();</script>";
exit;
}
function TrimGet($name){
if (isset($_GET[$name])){
return stripslashes_if_gpc_magic_quotes(trim($_GET[$name]));
}
if (isset($_POST[$name])){
return stripslashes_if_gpc_magic_quotes(trim($_POST[$name]));
}
return "";
}
function stripslashes_if_gpc_magic_quotes( $str ) {
if(get_magic_quotes_gpc()) {
return stripslashes($str);
} else {
return $str;
}}
function WriteConfig(){
$s_License="";
if (isset($GLOBALS["sLicense"])){
$s_License = $GLOBALS["sLicense"];
}
$sConfig = "<"."?php\r\n";
$sConfig = $sConfig."\r\n";
$sConfig = $sConfig."$"."sLicense = \"".$s_License."\";\r\n";
$sConfig = $sConfig."\r\n";
$sConfig = $sConfig."$"."sUsername = \"".$GLOBALS["sUsername"]."\";\r\n";
$sConfig = $sConfig."$"."sPassword = \"".$GLOBALS["sPassword"]."\";\r\n";
$sConfig = $sConfig."\r\n";
if (isset($GLOBALS["sWordEqVersion"])){
$sConfig = $sConfig."$"."sWordEqVersion = \"".$GLOBALS["sWordEqVersion"]."\";\r\n";
}
if (isset($GLOBALS["sWordEqLicense"])){
$sConfig = $sConfig."$"."sWordEqLicense = \"".$GLOBALS["sWordEqLicense"]."\";\r\n";
}
if (isset($GLOBALS["sWordEqVersion"]) || isset($GLOBALS["sWordEqLicense"])){
$sConfig = $sConfig."\r\n";
}
$nConfigStyle = 0;
$sConfigStyle = "";
$nConfigToolbar = 0;
$sConfigToolbar = "";
for ($i=1;$i<=count($GLOBALS["aStyle"]);$i++){
if ($GLOBALS["aStyle"][$i] != "") {
$aTmpStyle = explode("|||", $GLOBALS["aStyle"][$i]);
if ($aTmpStyle[0] != "") {
$nConfigStyle = $nConfigStyle + 1;
$sConfigStyle = $sConfigStyle."$"."aStyle[".$nConfigStyle."] = \"".$GLOBALS["aStyle"][$i]."\";\r\n";
$s_Order = "";
$s_ID = "";
for ($n=1;$n<=count($GLOBALS["aToolbar"]);$n++){
if ($GLOBALS["aToolbar"][$n] != ""){
$aTmpToolbar = explode("|||", $GLOBALS["aToolbar"][$n]);
if ($aTmpToolbar[0] == $i) {
if ($s_ID != ""){
$s_ID = $s_ID."|";
$s_Order = $s_Order."|";
}
$s_ID = $s_ID.$n;
$s_Order = $s_Order.$aTmpToolbar[3];
}}}
if ($s_ID != ""){
$a_ID = explode("|", $s_ID);
$a_Order = explode("|", $s_Order);
$a_ID = doSort($a_ID, $a_Order);
for ($n=0;$n<count($a_ID);$n++){
$nConfigToolbar = $nConfigToolbar + 1;
$aTmpToolbar = explode("|||", $GLOBALS["aToolbar"][$a_ID[$n]]);
$sTmpToolbar = $nConfigStyle."|||".$aTmpToolbar[1]."|||".$aTmpToolbar[2]."|||".$aTmpToolbar[3];
$sConfigToolbar = $sConfigToolbar."$"."aToolbar[".$nConfigToolbar."] = \"".$sTmpToolbar."\";\r\n";
}}}}}
$sConfig = $sConfig.$sConfigStyle."\r\n".$sConfigToolbar."\r\n?".">";
WriteFile("../php/config.php", $sConfig);
}
function WriteFile($s_FileName, $s_Text){
if (!$handle = fopen($s_FileName, 'w')) {
exit;
}
if (fwrite($handle, $s_Text) === FALSE) {
exit;
}
fclose($handle);
}
function doSort($aryValue, $aryOrder){
$KeepChecking = true;
while ($KeepChecking == true){
$KeepChecking = false;
for ($i=0; $i<count($aryOrder);$i++){
if ($i == count($aryOrder)-1){
break 1;
}
if ($aryOrder[$i] > $aryOrder[$i+1]){
$FirstOrder = $aryOrder[$i];
$SecondOrder = $aryOrder[$i+1];
$aryOrder[$i] = $SecondOrder;
$aryOrder[$i+1] = $FirstOrder;
$FirstValue = $aryValue[$i];
$SecondValue = $aryValue[$i+1];
$aryValue[$i] = $SecondValue;
$aryValue[$i+1] = $FirstValue;
$KeepChecking = true;
}}}
return $aryValue;
}
function GoUrl($url){
echo "<script language=javascript>location.href=\"".$url."\";</script>";
exit;
}
function InitSelect($s_FieldName, $a_Name, $a_Value, $v_InitValue, $s_AllName, $s_Attribute){
$s_Result = "<select name='".$s_FieldName."' size=1 ".$s_Attribute.">";
if ($s_AllName != "") {
$s_Result = $s_Result."<option value=''>".$s_AllName."</option>";
}
for ($i=0;$i<count($a_Name);$i++){
$s_Result = $s_Result."<option value=\"".HTMLEncode($a_Value[$i])."\"";
if ($a_Value[$i] == $v_InitValue) {
$s_Result = $s_Result." selected";
}
$s_Result = $s_Result.">".HTMLEncode($a_Name[$i])."</option>";
}
$s_Result = $s_Result."</select>";
return $s_Result;
}
function InitCheckBox($s_FieldName, $s_Value, $s_InitValue){
$s_Result = "";
if ($s_Value == $s_InitValue){
$s_Result = "<input type=checkbox name='".$s_FieldName."' value='".$s_Value."' checked>";
}else{
$s_Result = "<input type=checkbox name='".$s_FieldName."' value='".$s_Value."'>";
}
return $s_Result;
}
function CheckAndUpdateConfig(){	
if (!isset($GLOBALS["sLicense"])){
WriteConfig();
echo "<script type='text/javascript'>top.location.href='default.php'</script>";
exit;
return;
}
$n_Old = count(explode("|||", $GLOBALS["aStyle"][1]))-1;
$n_New = 114;
if (($n_Old<66) || ($n_Old>=$n_New)){
return;
}
for ($i=1; $i<=count($GLOBALS["aStyle"]); $i++){
$s = "";
$a = explode("|||", $GLOBALS["aStyle"][$i]);
for ($j=$n_Old+1; $j<=$n_New; $j++){
$s = $s . "|||";
switch($j){
case 67:
case 68:
case 69:
$s = $s."0";
break;
case 70:
$s = $s."";
break;
case 71:
switch($a[21]){
case "1":
$s = $s."{yyyy}/";
break;
case "2":
$s = $s."{yyyy}{mm}/";
break;
case "3":
$s = $s."{yyyy}{mm}{dd}/";
break;
default:
$s = $s."";
break;
}
break;
case 72:
$s = $s."1";
break;
case 73:
$s = $s."{page}";
break;
case 74:
$s = $s."0";
break;
case 75:
$s = $s."2000";
break;
case 76:
$s = $s."1";
break;
case 77:
$s = $s."0";
break;
case 78:
$s = $s."";
break;
case 79:
$s = $s."0";
break;
case 80:
$s = $s."200";
break;
case 81:
$s = $s."1";
break;
case 82:
$s = $s."2";
break;
case 83:
$s = $s."1";
break;
case 84:
$s = $s."1";
break;
case 85:
$s = $s."1";
break;
case 86:
$s = $s."0";
break;
case 87:
$s = $s."";
break;
case 88:
$s = $s."0";
break;
case 89:
case 90:
case 91:
case 92:
case 93:
case 94:
$s = $s."";
break;
case 95:
$s = $s."1";
break;
case 96:
case 97:
$s = $s."";
break;
case 98:
$s = $s."300";
break;
case 99:
$s = $s."1";
break;
case 100:
case 101:
case 102:
case 103:
case 104:
$s = $s."";
break;
case 105:
$s = $s."1";
break;
case 106:
$s = $s."";
break;
case 107:
case 108:
case 109:
if (count($a)>84){
if ($a[84]=="1"){
$s = $s."1";
}else{
$s = $s."0";
}}else{
$s = $s."1";
}
break;
case 110:
$s = $s."0";
break;
case 111:
case 112:
$s = $s."";
break;
case 113:
case 114:
$s = $s."1";
break;
}}
$GLOBALS["aStyle"][$i] = $GLOBALS["aStyle"][$i].$s;
}
WriteConfig();
}
function CheckLicense(){
$s_Domain=GetDomain();
if (($s_Domain=="127.0.0.1") || ($s_Domain=="localhost")){
return true;
}
if ($GLOBALS["sLicense"]==""){
return false;
}
$aa = explode(";", $GLOBALS["sLicense"]);
for($i=0; $i<count($aa); $i++){
if (strpos($aa[$i], ",") !== false){
$a = explode(",", $aa[$i]);
}else{
$a = explode(":", $aa[$i]);
}
if (count($a)==8){
if (strlen($a[7])==32){
if ($a[0]=="3"){
if (($a[6]==$s_Domain) || ("." . $a[6]==substr($s_Domain,-strlen($a[6])-1))){
return true;
}}else{
if (($a[6]==$s_Domain) || ("www." . $a[6]==$s_Domain)){
return true;
}}}}}
return false;
}
function GetDomain(){
$s_Domain = isset($_SESSION["eWebEditor_Host"]) ? $_SESSION["eWebEditor_Host"] : "";
if ($s_Domain==""){
$s_Domain = strtolower($_SERVER["SERVER_NAME"]);
if ((strpos($s_Domain, ":") !== false) && (strpos($s_Domain, "]") === false)){
$s_Domain = "[".$s_Domain."]";
}}
return $s_Domain;
}
function GoLicense(){
eWebEditor_Header();
echo "<script type='text/javascript'>alert('未授权：需要输入正版授权序列号后才可使用！');location.href='modilicense.php';</script>";
exit;
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
function HTMLEncode($str){
return htmlspecialchars($str, ENT_COMPAT, "ISO-8859-1");
}
?>