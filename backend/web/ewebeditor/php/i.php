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
$sAction = strtoupper(TrimGet("action"));
switch ($sAction){
case "LICENSE":
ShowLicense();
break;
case "WORDEQLICENSE":
ShowWordEqLicense();
break;
case "CONFIG":
ShowConfig();
break;
}
function ShowLicense(){
if ($GLOBALS["sLicense"]==""){
return;
}
$r = TrimGet("r");
if (strlen($r)<10){
return;
}
$s_Domain=GetDomain();
if (($s_Domain=="127.0.0.1") || ($s_Domain=="localhost")){
return;
}
$ret="";
$s_Sep="";
$aa = explode(";", $GLOBALS["sLicense"]);
for($i=0; $i<count($aa); $i++){
if (strpos($aa[$i], ",") !== false) {
$s_Sep = ",";
}else{
$s_Sep = ":";
}
$a = explode($s_Sep, $aa[$i]);
if (count($a)==8){
if (strlen($a[7])==32){
$b=false;
if ($a[0]=="3"){
if (($a[6]==$s_Domain) || ("." . $a[6]==substr($s_Domain,-strlen($a[6])-1))){
$b=true;
}}else{
if (($a[6]==$s_Domain) || ("www." . $a[6]==$s_Domain)){
$b=true;
}}
if ($b){
for($j=0; $j<7; $j++){
$ret=$ret.$a[$j].$s_Sep;
}
$ret=$ret . MD5_16(substr($a[7],0,16) . $r) . MD5_16(substr($a[7],16,16) . $r);
break;
}}}}
echo $ret;
}
function ShowWordEqLicense(){
$s_WordEqLicense = "";
if (isset($GLOBALS["sWordEqLicense"])){
$s_WordEqLicense = $GLOBALS["sWordEqLicense"];
}
if ($s_WordEqLicense==""){
return;
}
$r = TrimGet("r");
if (strlen($r)<10){
return;
}
$s_Domain=GetDomain();
if (($s_Domain=="127.0.0.1") || ($s_Domain=="localhost")){
return;
}
$ret="";
$s_Sep="";
$aa = explode(";", $s_WordEqLicense);
for($i=0; $i<count($aa); $i++){
if (strpos($aa[$i], ",") !== false) {
$s_Sep = ",";
}else{
$s_Sep = ":";
}
$a = explode($s_Sep, $aa[$i]);
if (count($a)==4){
if (strlen($a[3])==32){
$b=false;
if ($a[0]=="3"){
if (($a[2]==$s_Domain) || ("." . $a[2]==substr($s_Domain,-strlen($a[2])-1))){
$b=true;
}}else{
if (($a[2]==$s_Domain) || ("www." . $a[2]==$s_Domain)){
$b=true;
}}
if ($b){
for($j=0; $j<3; $j++){
$ret=$ret.$a[$j].$s_Sep;
}
$ret=$ret . MD5_16(substr($a[3],0,16) . $r) . MD5_16(substr($a[3],16,16) . $r);
break;
}}}}
echo $ret;
}
function ShowConfig(){
$s_Domain=GetDomain();
$s_License="";
if (CheckLicense($s_Domain)){
$s_License = "ok";
}
$n_StyleID=0;
$s_StyleName = TrimGet("style");
$b = false;
$numElements = count($GLOBALS["aStyle"]);
for($i=1; $i<=$numElements; $i++){
$aTmpStyle = explode("|||", $GLOBALS["aStyle"][$i]);
if (strtolower($s_StyleName)==strtolower($aTmpStyle[0])){
$n_StyleID = $i;
$b = true;
break;
}}
if (!$b){
return;
}
$s_WordEqVersion = "";
if (isset($GLOBALS["sWordEqVersion"])){
$s_WordEqVersion = $GLOBALS["sWordEqVersion"];
}
$s_SKey = TrimGet("skey");
$s_SParams = "";
$ss_PathCusDir = "";
if (($aTmpStyle[61]=="2") && ($s_SKey!="")){
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
$aTmpStyle[11] = $ss_FileSize;
$aTmpStyle[12] = $ss_FileSize;
$aTmpStyle[13] = $ss_FileSize;
$aTmpStyle[14] = $ss_FileSize;
$aTmpStyle[15] = $ss_FileSize;
$aTmpStyle[45] = $ss_FileSize;
}else{
$ss_FileSize = "";
}
if (($ss_FileBrowse == "0") || ($ss_FileBrowse == "1")){
$aTmpStyle[43] = $ss_FileBrowse;
}else{
$ss_FileBrowse = "";
}
if (is_numeric($ss_SpaceSize)){
$aTmpStyle[78] = $ss_SpaceSize;
}else{
$ss_SpaceSize = "";
}
if ($ss_PathMode != ""){
$aTmpStyle[19] = $ss_PathMode;
}
if ($ss_PathUpload != ""){
$aTmpStyle[3] = $ss_PathUpload;
}
if ($ss_PathCode != ""){
$aTmpStyle[23] = $ss_PathCode;
}
if ($ss_PathView != ""){
$aTmpStyle[22] = $ss_PathView;
}
$s_SParams = $ss_FileSize . "," . $ss_FileBrowse . "," . $ss_SpaceSize . "," . $ss_SpacePath . "," . $ss_PathMode . "," . $ss_PathUpload . "," . $ss_PathCusDir . "," . $ss_PathCode . "," . $ss_PathView;
$s_SParams = MD5_16($aTmpStyle[70] . $s_SParams) . "," . $s_SParams;
}
$s_FSPath = $aTmpStyle[112];
$s_FSL = "";
$s_FSDomain = "";
if ($s_FSPath!=""){
$s_FSDomain = GetDomainFromUrl($s_FSPath);
if ($s_FSDomain!=""){
if (CheckLicense($s_FSDomain)){
$s_FSL = "ok";
}}}
$s_Cookie = "";
if ($aTmpStyle[114]=="2"){
$s_Cookie = GetServerVariables("HTTP_COOKIE");
}else{
$s_Cookie = $aTmpStyle[114];
}
$ret = "";
$ret = $ret."config.FixWidth = \"".$aTmpStyle[1]."\";\r\n";
if ($aTmpStyle[19]=="3"){
$ret = $ret."config.UploadUrl = \"".$aTmpStyle[23]."\";\r\n";
}else{
$ret = $ret."config.UploadUrl = \"".$aTmpStyle[3]."\";\r\n";
}
$ret = $ret."config.InitMode = \"".$aTmpStyle[18]."\";\r\n";
$ret = $ret."config.AutoDetectPaste = \"".$aTmpStyle[17]."\";\r\n";
$ret = $ret."config.BaseUrl = \"".$aTmpStyle[19]."\";\r\n";
$ret = $ret."config.BaseHref = \"".$aTmpStyle[22]."\";\r\n";
$ret = $ret."config.AutoRemote = \"".$aTmpStyle[24]."\";\r\n";
$ret = $ret."config.ShowBorder = \"".$aTmpStyle[25]."\";\r\n";
$ret = $ret."config.StateFlag = \"".$aTmpStyle[16]."\";\r\n";
$ret = $ret."config.SBCode = \"".$aTmpStyle[62]."\";\r\n";
$ret = $ret."config.SBEdit = \"".$aTmpStyle[63]."\";\r\n";
$ret = $ret."config.SBText = \"".$aTmpStyle[64]."\";\r\n";
$ret = $ret."config.SBView = \"".$aTmpStyle[65]."\";\r\n";
$ret = $ret."config.SBSize = \"".$aTmpStyle[76]."\";\r\n";
$ret = $ret."config.EnterMode = \"".$aTmpStyle[66]."\";\r\n";
$ret = $ret."config.Skin = \"".$aTmpStyle[2]."\";\r\n";
$ret = $ret."config.AutoDetectLanguage = \"".$aTmpStyle[27]."\";\r\n";
$ret = $ret."config.DefaultLanguage = \"".$aTmpStyle[28]."\";\r\n";
$ret = $ret."config.AllowBrowse = \"".$aTmpStyle[43]."\";\r\n";
$ret = $ret."config.AllowImageSize = \"".$aTmpStyle[13]."\";\r\n";
$ret = $ret."config.AllowFlashSize = \"".$aTmpStyle[12]."\";\r\n";
$ret = $ret."config.AllowMediaSize = \"".$aTmpStyle[14]."\";\r\n";
$ret = $ret."config.AllowFileSize = \"".$aTmpStyle[11]."\";\r\n";
$ret = $ret."config.AllowRemoteSize = \"".$aTmpStyle[15]."\";\r\n";
$ret = $ret."config.AllowLocalSize = \"".$aTmpStyle[45]."\";\r\n";
$ret = $ret."config.AllowImageExt = \"".$aTmpStyle[8]."\";\r\n";
$ret = $ret."config.AllowFlashExt = \"".$aTmpStyle[7]."\";\r\n";
$ret = $ret."config.AllowMediaExt = \"".$aTmpStyle[9]."\";\r\n";
$ret = $ret."config.AllowFileExt = \"".$aTmpStyle[6]."\";\r\n";
$ret = $ret."config.AllowRemoteExt = \"".$aTmpStyle[10]."\";\r\n";
$ret = $ret."config.AllowLocalExt = \"".$aTmpStyle[44]."\";\r\n";
$ret = $ret."config.AreaCssMode = \"".$aTmpStyle[67]."\";\r\n";
$ret = $ret."config.SLTFlag = \"".$aTmpStyle[29]."\";\r\n";
$ret = $ret."config.SLTMinSize = \"".$aTmpStyle[30]."\";\r\n";
$ret = $ret."config.SLTOkSize = \"".$aTmpStyle[31]."\";\r\n";
$ret = $ret."config.SLTMode = \"".$aTmpStyle[69]."\";\r\n";
$ret = $ret."config.SLTCheckFlag = \"".$aTmpStyle[77]."\";\r\n";
$ret = $ret."config.SYWZFlag = \"".$aTmpStyle[32]."\";\r\n";
$ret = $ret."config.SYTPFlag = \"".$aTmpStyle[52]."\";\r\n";
$ret = $ret."config.FileNameMode = \"".$aTmpStyle[68]."\";\r\n";
$ret = $ret."config.PaginationMode = \"".$aTmpStyle[72]."\";\r\n";
$ret = $ret."config.PaginationKey = \"".$aTmpStyle[73]."\";\r\n";
$ret = $ret."config.PaginationAutoFlag = \"".$aTmpStyle[74]."\";\r\n";
$ret = $ret."config.PaginationAutoNum = \"".$aTmpStyle[75]."\";\r\n";
$ret = $ret."config.SParams = \"".str_replace("\\","\\\\",$s_SParams)."\";\r\n";
$ret = $ret."config.SpaceSize = \"".$aTmpStyle[78]."\";\r\n";
$ret = $ret."config.MFUBlockSize = \"".$aTmpStyle[80]."\";\r\n";
$ret = $ret."config.MFUEnable = \"".$aTmpStyle[81]."\";\r\n";
$ret = $ret."config.CodeFormat = \"".$aTmpStyle[82]."\";\r\n";
$ret = $ret."config.TB2Flag = \"".$aTmpStyle[83]."\";\r\n";
$ret = $ret."config.TB2Code = \"".$aTmpStyle[84]."\";\r\n";
$ret = $ret."config.TB2Max = \"".$aTmpStyle[85]."\";\r\n";
$ret = $ret."config.ShowBlock = \"".$aTmpStyle[86]."\";\r\n";
$ret = $ret."config.WIIMode = \"".$aTmpStyle[95]."\";\r\n";
$ret = $ret."config.QFIFontName = \"".$aTmpStyle[96]."\";\r\n";
$ret = $ret."config.QFIFontSize = \"".$aTmpStyle[97]."\";\r\n";
$ret = $ret."config.UIMinHeight = \"".$aTmpStyle[98]."\";\r\n";
$ret = $ret."config.SYVNormal = \"".$aTmpStyle[99]."\";\r\n";
$ret = $ret."config.SYVLocal = \"".$aTmpStyle[100]."\";\r\n";
$ret = $ret."config.SYVRemote = \"".$aTmpStyle[101]."\";\r\n";
$ret = $ret."config.AutoDonePasteWord = \"".$aTmpStyle[102]."\";\r\n";
$ret = $ret."config.AutoDonePasteExcel = \"".$aTmpStyle[103]."\";\r\n";
$ret = $ret."config.AutoDoneQuickFormat = \"".$aTmpStyle[104]."\";\r\n";
$ret = $ret."config.WIAPI = \"".$aTmpStyle[105]."\";\r\n";
$ret = $ret."config.AutoDonePasteOption = \"".$aTmpStyle[106]."\";\r\n";
$ret = $ret."config.TB2Edit = \"".$aTmpStyle[107]."\";\r\n";
$ret = $ret."config.TB2Text = \"".$aTmpStyle[108]."\";\r\n";
$ret = $ret."config.TB2View = \"".$aTmpStyle[109]."\";\r\n";
$ret = $ret."config.Cookie = \"".$s_Cookie."\";\r\n";
$ret = $ret."config.CertIssuer = \"".GetServerVariables("CERT_ISSUER")."\";\r\n";
$ret = $ret."config.CertSubject = \"".GetServerVariables("CERT_SUBJECT")."\";\r\n";
$ret = $ret."config.WordEqVersion = \"".$s_WordEqVersion."\";\r\n";
$ret = $ret."config.WordEqImport = \"".$aTmpStyle[110]."\";\r\n";
$ret = $ret."config.ResMovePath = \"".$aTmpStyle[111]."\";\r\n";
$ret = $ret."config.FSPath = \"".$s_FSPath."\";\r\n";
$ret = $ret."config.FSL = \"".$s_FSL."\";\r\n";
$ret = $ret."config.UseProxy = \"".$aTmpStyle[113]."\";\r\n";
$ret = $ret."config.L = \"".$s_License."\";\r\n";
$ret = $ret."config.ServerExt = \"php\";\r\n";
$ret = $ret."config.Charset = \"utf-8\";\r\n";
if ($ss_PathCusDir!=""){
$ret = $ret."config.CusDir = \"".$ss_PathCusDir."\";\r\n";
}
$ret = $ret."\r\n";
$ret = $ret."config.Toolbars = [\r\n";
$s_Order = "";
$s_ID = "";
for ($n=1;$n<=count($GLOBALS["aToolbar"]);$n++){
if ($GLOBALS["aToolbar"][$n] != "") {
$aTmpToolbar = explode("|||", $GLOBALS["aToolbar"][$n]);
if ((int)$aTmpToolbar[0] == $n_StyleID) {
if ($s_ID != "") {
$s_ID = $s_ID."|";
$s_Order = $s_Order."|";
}
$s_ID = $s_ID.$n;
$s_Order = $s_Order.$aTmpToolbar[3];
}}}
if ($s_ID != "") {
$a_ID = explode("|", $s_ID);
$a_Order = explode("|", $s_Order);
for ($n=0;$n<count($a_Order);$n++){
$a_Order[$n] = (int)($a_Order[$n]);
$a_ID[$n] = (int)($a_ID[$n]);
}
for ($n=0;$n<count($a_ID);$n++){
$aTmpToolbar = explode("|||", $GLOBALS["aToolbar"][$a_ID[$n]]);
$aTmpButton = explode("|", $aTmpToolbar[1]);
$n_Count = count($aTmpButton);
if ($n>0){
$ret = $ret.",\r\n";
}
$ret = $ret."\t[";
for ($i=0;$i<$n_Count;$i++){
if ($i > 0){
$ret = $ret.", ";
}
$ret = $ret."\"".$aTmpButton[$i]."\"";
}
$ret = $ret."]";
}}
$ret = $ret."\r\n];\r\n";
header("Content-Type: application/x-javascript");
echo $ret;
}
function CheckLicense($s_Domain){
if (($s_Domain=="127.0.0.1") || ($s_Domain=="localhost")){
return true;
}
if ($GLOBALS["sLicense"]==""){
return false;
}
$aa = explode(";", $GLOBALS["sLicense"]);
for($i=0; $i<count($aa); $i++){
$s_Sep="";
if (strpos($aa[$i], ",") !== false) {
$s_Sep = ",";
}else{
$s_Sep = ":";
}
$a = explode($s_Sep, $aa[$i]);
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
$s_Domain = DeCode9193(strtolower(TrimGet("h")));
if ($s_Domain==""){
$s_Domain = strtolower($_SERVER["SERVER_NAME"]);
if ((strpos($s_Domain, ":") !== false) && (strpos($s_Domain, "]") === false)){
$s_Domain = "[".$s_Domain."]";
}}
return $s_Domain;
}
function GetDomainFromUrl($s_Url){
$parseUrl = parse_url($s_Url);
return ($parseUrl["host"] ? $parseUrl["host"] : "");
}
function GetServerVariables($s_Key){
if (isset($_SERVER[$s_Key])){
$s_Ret = $_SERVER[$s_Key];
$s_Ret = str_replace("\\", "\\\\", $s_Ret);
$s_Ret = str_replace("\"", "\\\"", $s_Ret);
$s_Ret = str_replace("'", "\\'", $s_Ret);
$s_Ret = str_replace(chr(13), "", $s_Ret);
$s_Ret = str_replace(chr(10), "", $s_Ret);
return $s_Ret;
}else{
return "";
}}
?>