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
if (!CheckLicense()){
GoLicense();
exit;
}
$sPosition = $sPosition."上传文件管理";
eWebEditor_Header();
eWebEditor_Content();
eWebEditor_Footer();
function eWebEditor_Content(){
InitParam();
switch ($GLOBALS["sAction"]){
case "DELALL":
DoDelAll();
break;
case "DEL":
DoDel();
break;
case "DELFOLDER":
DoDelFolder();
break;
}
ShowList();
}
function ShowList(){
$sCurrPage = TrimGet("page");
$s_ViewMode = TrimGet("d_viewmode");
$s_FormViewMode = InitSelect("d_viewmode", explode("|", "预览模式|列表模式"), explode("|", "|list"), $s_ViewMode, "", "onchange=\"location.href='?id=".$GLOBALS["sStyleID"]."&d_viewmode='+this.value+'&dir=".urlencode($GLOBALS["sDir"])."&page=".$sCurrPage."'\"");
echo "<table border=0 cellspacing=1 align=center class=navi>".
"<form action='?' method=post name=queryform>".
"<tr><th>".$GLOBALS["sPosition"]."</th></tr>".
"<tr><td align=right><b>显示模式：</b>".$s_FormViewMode." <b>选择样式目录：</b><select name='id' size=1 onchange=\"this.form.submit()\">".InitSelectStyle($GLOBALS["sStyleID"], "选择...")."</select></td></tr>".
"</form></table><br>";
if ($GLOBALS["sCurrDir"] == "") {
return;
}
$s_QueryStr = "?id=".$GLOBALS["sStyleID"]."&d_viewmode=".$s_ViewMode."&dir=".urlencode($GLOBALS["sDir"]);
echo "<table border=0 cellspacing=1 class=list align=center>".
"<form action='".$s_QueryStr."&action=del' method=post name=myform>".
"<tr align=center>".
"<th width='10%'>类型</th>".
"<th width='40%'>文件地址</th>".
"<th width='10%'>大小</th>".
"<th width='15%'>最后访问</th>".
"<th width='15%'>上传日期</th>".
"<th width='10%'>删除</th>".
"</tr>";
$nPageSize = 20;
if (($sCurrPage == "") || !is_numeric($sCurrPage)) {
$nCurrPage = 1;
}else{
$nCurrPage = (int)$sCurrPage;
}
if (!is_dir($GLOBALS["sCurrDir"])) {
echo "<tr><td colspan=6>无效的目录！</td></tr></table>";
exit;
}
if ($GLOBALS["sDir"] != "") {
echo "<tr align=center>".
"<td><img border=0 src='images/file/folderback.gif'></td>".
"<td align=left colspan=5><a href=\"?id=".$GLOBALS["sStyleID"]."&d_viewmode=".$s_ViewMode."&dir=";
if (strrpos($GLOBALS["sDir"], "/") !== false) {
echo urlencode(substr($GLOBALS["sDir"], 0, strrpos($GLOBALS["sDir"], "/")));
}
echo "\">返回上一级目录</a></td></tr>";
}
if ($handle = opendir($GLOBALS["sCurrDir"])) {
while (false !== ($file = readdir($handle))) {
$sFileType = filetype($GLOBALS["sCurrDir"]."/".$file);
switch ($sFileType){
case "dir":
if (($file!=".")&&($file!="..")){
$oDirs[] = $file;
}
break;
case "file":
$oFiles[] = $file;
break;
default:
}}}
if (isset($oDirs)){
foreach( $oDirs as $oDir){
$oDir = Syscode2Pagecode($oDir,true);
echo "<tr align=center>".
"<td><img border=0 src='images/file/folder.gif'></td>".
"<td align=left colspan=4><a href=\"?id=".$GLOBALS["sStyleID"]."&d_viewmode=".$s_ViewMode."&dir=";
if ($GLOBALS["sDir"] != "") {
echo urlencode($GLOBALS["sDir"]."/".$oDir);
}else{
echo urlencode($oDir);
}
echo "\">".$oDir."</a></td>".
"<td><a href='".$s_QueryStr."&action=delfolder&foldername=".urlencode($oDir)."'>删除</a></td></tr>";
}}
if (isset($oFiles)){
$nFileNum = count($oFiles);
}else{
$nFileNum = 0;
}
$nPageNum = (int)($nFileNum / $nPageSize);
if (($nFileNum % $nPageSize) > 0) {
$nPageNum = $nPageNum+1;
}
if ($nCurrPage > $nPageNum) {
$nCurrPage = $nPageNum;
}
if ($nFileNum>0){
$i = 0;
$m = 0;
$n = 0;
foreach( $oFiles as $oFile){
$i = $i + 1;
if (($i > ($nCurrPage - 1) * $nPageSize) && ($i <= $nCurrPage * $nPageSize)) {
$sFileName = $GLOBALS["sCurrDir"].$oFile;
$oFile = Syscode2Pagecode($oFile,true);
if ($s_ViewMode=="list"){
echo "<tr align=center>".
"<td>".FileName2Pic($oFile)."</td>".
"<td align=left><a href=\"".$GLOBALS["sCurrPath"].$oFile."\" target=_blank>".$oFile."</a></td>".
"<td>".filesize($sFileName)." B </td>".
"<td>".date("Y-m-d", fileatime($sFileName))."</td>".
"<td>".date("Y-m-d", filectime($sFileName))."</td>".
"<td><input type=checkbox name='delfilename[]' value=\"".$oFile."\"></td></tr>";
}else{
$n = $n + 1;
$m = $n % 4;
if ($n == 1){
echo "<tr align=center><td colspan=6><table border=0 cellspacing=1 width='98%' class=list>";
}
if ($m == 1){
echo "<tr>";
}
echo "<td width='25%' valign=top align=center><table border=0 cellspacing=1>".
"<tr><td width=200 height=200 align=center>".File2Preview($GLOBALS["sCurrPath"], $oFile)."</td></tr>".
"<tr><td>文件名称：<a href=\"".$GLOBALS["sCurrPath"].$oFile."\" target=_blank>".$oFile."</a></td></tr>".
"<tr><td>文件大小：".filesize($sFileName)." B</td></tr>".
"<tr><td>最后访问：".date("Y-m-d", fileatime($sFileName))."</td></tr>".
"<tr><td>创建日期：".date("Y-m-d", filectime($sFileName))."</td></tr>".
"<tr><td>操作选择：<input type=checkbox name='delfilename[]' value=\"".$oFile."\"></td></tr>".
"</table></td>";
if ($m == 0){
echo "</tr>";
}}}elseif($i > $nCurrPage * $nPageSize){
break;
}}
if ($s_ViewMode != "list"){
if ($n > 0){
if ($m != 0){
for ($ii=1;$ii<=(4-$m);$ii++){
echo "<td width='25%'>&nbsp;</td>";
}
echo "</tr>";
}
echo "</table></td></tr>";
}}}
if ($nFileNum <= 0) {
echo "<tr><td colspan=6>指定目录下现在还没有文件！</td></tr>";
}
if ($nFileNum > 0) {
echo "<tr><td colspan=6><table border=0 cellpadding=3 cellspacing=0 width='100%'><tr><td>";
if ($nCurrPage > 1) {
echo "<a href='".$s_QueryStr."&page=1'>首页</a>&nbsp;&nbsp;<a href='".$s_QueryStr."&page=".($nCurrPage - 1)."'>上一页</a>&nbsp;&nbsp;";
}else{
echo "首页&nbsp;&nbsp;上一页&nbsp;&nbsp;";
}
if ($nCurrPage < $nPageNum) {
echo "<a href='".$s_QueryStr."&page=".($nCurrPage + 1)."'>下一页</a>&nbsp;&nbsp;<a href='".$s_QueryStr."&page=".$nPageNum."'>尾页</a>";
}else{
echo "下一页&nbsp;&nbsp;尾页";
}
echo "&nbsp;&nbsp;&nbsp;&nbsp;共<b>".$nFileNum."</b>个&nbsp;&nbsp;页次:<b><span class=highlight2>".$nCurrPage."</span>/".$nPageNum."</b>&nbsp;&nbsp;<b>".$nPageSize."</b>个文件/页";
echo "&nbsp;&nbsp;转到:";
echo "<select name='page' size=1 onchange=\"location.href='" . $s_QueryStr . "&page='+this.options[this.selectedIndex].value;\">";
for($n=1; $n<=$nPageNum; $n++){
echo "<option value='" . $n . "'";
if ($n == $nCurrPage){
echo " selected";
}
echo ">第" . $n . "页</option>";
}
echo "</select>";
echo "</td><td align=right><input type=checkbox id=b_selectall onclick='doCheckAll(this)'><label for=b_selectall>全选</label>&nbsp; <input type=submit name=b value='删除选定的文件'> <input type=button name=b1 value='清空本目录所有文件' onclick=\"javascript:if (confirm('你确定要清空所有文件吗？')) {location.href='".$s_QueryStr."&action=delall';}\"></td></tr></table></td></tr>";
}
echo "</form></table>";
}
function DoDel(){
if (isset($_POST["delfilename"])){
foreach($_POST["delfilename"] as $sFileName){
$sMapFileName = $GLOBALS["sCurrDir"].Syscode2Pagecode($sFileName,false);
if(file_exists($sMapFileName)){
unlink($sMapFileName);
}}}}
function DoDelAll(){
$dir = dir($GLOBALS["sCurrDir"]);
while(false !== ($sFileName=$dir->read())){
$sMapFileName = $GLOBALS["sCurrDir"].$sFileName;
if(file_exists($sMapFileName)){
if (filetype($sMapFileName)=="file"){
unlink($sMapFileName);
}}}
$dir->close();
}
function DoDelFolder(){
$sFolderName = Syscode2Pagecode(TrimGet("foldername"),false);
$sFolderName = $GLOBALS["sCurrDir"].$sFolderName;
deldir($sFolderName);
}
function deldir($dir){
$handle = opendir($dir);
while (false!==($FolderOrFile = readdir($handle))){
if($FolderOrFile != "." && $FolderOrFile != ".."){
if(is_dir("$dir/$FolderOrFile")){
deldir("$dir/$FolderOrFile");
}else{
unlink("$dir/$FolderOrFile");
}}}
closedir($handle);
if(rmdir($dir)){
$success = true;
}
return $success;  
} 
function FileName2Pic($sFileName){
$sExt = strtoupper(substr($sFileName, strrpos($sFileName, ".")+1));
switch ($sExt){
case "TXT":
$sPicName = "txt.gif";
break;
case "CHM":
case "HLP":
$sPicName = "hlp.gif";
break;
case "DOC":
$sPicName = "doc.gif";
break;
case "PDF":
$sPicName = "pdf.gif";
break;
case "MDB":
$sPicName = "mdb.gif";
break;
case "GIF":
$sPicName = "gif.gif";
break;
case "JPG":
$sPicName = "jpg.gif";
break;
case "BMP":
$sPicName = "bmp.gif";
break;
case "PNG":
$sPicName = "pic.gif";
break;
case "ASP":
case "JSP":
case "JS":
case "PHP":
case "PHP3":
case "ASPX":
$sPicName = "code.gif";
break;
case "HTM":
case "HTML":
case "SHTML":
$sPicName = "htm.gif";
break;
case "ZIP":
$sPicName = "zip.gif";
break;
case "RAR":
$sPicName = "rar.gif";
break;
case "EXE":
$sPicName = "exe.gif";
break;
case "AVI":
$sPicName = "avi.gif";
break;
case "MPG":
case "MPEG":
case "ASF":
$sPicName = "mp.gif";
break;
case "RA":
case "RM":
$sPicName = "rm.gif";
break;
case "MP3":
$sPicName = "mp3.gif";
break;
case "MID":
case "MIDI":
$sPicName = "mid.gif";
break;
case "WAV":
$sPicName = "audio.gif";
break;
case "XLS":
$sPicName = "xls.gif";
break;
case "PPT":
case "PPS":
$sPicName = "ppt.gif";
break;
case "SWF":
$sPicName = "swf.gif";
break;
default:
$sPicName = "unknow.gif";
break;
}
return "<img border=0 src='images/file/".$sPicName."'>";
}
function File2Preview($s_Path, $s_File){
$s_Result = "";
$s_PathFile = $s_Path.$s_File;
$sExt = strtoupper(substr($s_File, strrpos($s_File, ".")+1));
switch($sExt){
case "GIF":
case "JPG":
case "JPEG":
case "BMP":
case "PNG":
$s_Result = "<a href='".$s_PathFile."' target='_blank'><img border=0 src='".$s_PathFile."' width=180 height=180></a>";
break;
case "SWF":
$s_Result = "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0' width=180 height=180>".
"<param name='movie' value='".$s_PathFile."'>".
"<param name='quality' value='high'>".
"<embed src='".$s_PathFile."' quality='high' width=180 height=180 type='application/x-shockwave-flash\' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed>".
"</object>";
break;
default:
$s_Result = FileName2Pic($s_File);
break;
}
return $s_Result;
}
function InitSelectStyle($v_InitValue, $s_AllName){
$s_Result = "";
if ($s_AllName != "") {
$s_Result = $s_Result."<option value=''>".$s_AllName."</option>";
}
for ($i=1;$i<=count($GLOBALS["aStyle"]);$i++){
$aTemp = explode("|||", $GLOBALS["aStyle"][$i]);
$s_Result = $s_Result."<option value='".$i."'";
if ((string)$i == (string)$v_InitValue) {
$s_Result = $s_Result." selected";
}
$s_Result = $s_Result.">样式：".HTMLEncode($aTemp[0])."---目录：".HTMLEncode($aTemp[3])."</option>";
}
return $s_Result;
}
function InitParam(){
global $sStyleID, $sCurrDir, $sDir, $sCurrPath;
$sStyleID = TrimGet("id");
$s_UploadDir = "";
$a_CurrStyle = array();
if (is_numeric($sStyleID)) {
if ((int)($sStyleID) <= count($GLOBALS["aStyle"])) {
$a_CurrStyle = explode("|||", $GLOBALS["aStyle"][$sStyleID]);
$s_UploadDir = $a_CurrStyle[3];
}}
if ($s_UploadDir == "") {
$sStyleID = "";
$sCurrDir = "";
return;
}
$s_BaseUrl = $a_CurrStyle[19];
if ($s_BaseUrl=="3"){
$sCurrDir = $s_UploadDir;
$sCurrPath = $a_CurrStyle[23];
}else{
if (substr($s_UploadDir, 0, 1) != "/") {
$s_UploadDir = "../".$s_UploadDir;
}
$sCurrDir = realpath($s_UploadDir);
$sCurrPath = $s_UploadDir;
}
if (!is_dir($sCurrDir)){
$sCurrDir = "";
return;
}
if ((substr($sCurrDir, -1) != "/") && (substr($sCurrDir,-1) != "\\")){
$sCurrDir .= "/";
}
if (substr($sCurrPath, -1) != "/") {
$sCurrPath .= "/";
}
$sDir = TrimGet("dir");
if ($sDir != "") {
$ss_Dir = Syscode2Pagecode($sDir,false);
if (is_dir($sCurrDir.$ss_Dir)) {
$sCurrDir .= $ss_Dir."/";
$sCurrPath .= $sDir."/";
}else{
$sDir = "";
}}}
?>