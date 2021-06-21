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
$sPosition = $sPosition."样式管理";
if ($sAction == "STYLEPREVIEW"){
InitStyle();
ShowStylePreview();
exit;
}
eWebEditor_Header();
ShowPosition();
eWebEditor_Content();
eWebEditor_Footer();
function eWebEditor_Content(){
switch ($GLOBALS["sAction"]){
case "COPY":
InitStyle();
DoCopy();
ShowStyleList();
break;
case "STYLEADD":
ShowStyleForm("ADD");
break;
case "STYLESET":
InitStyle();
ShowStyleForm("SET");
break;
case "STYLEADDSAVE":
CheckStyleForm();
DoStyleAddSave();
break;
case "STYLESETSAVE":
CheckStyleForm();
DoStyleSetSave();
break;
case "STYLEDEL":
InitStyle();
DoStyleDel();
ShowStyleList();
break;
case "CODE":
InitStyle();
ShowStyleCode();
break;
case "TOOLBAR":
InitStyle();
ShowToolBarList();
break;
case "TOOLBARADD":
InitStyle();
DoToolBarAdd();
ShowToolBarList();
break;
case "TOOLBARMODI":
InitStyle();
DoToolBarModi();
ShowToolBarList();
break;
case "TOOLBARDEL":
InitStyle();
DoToolBarDel();
ShowToolBarList();
break;
case "BUTTONSET":
InitStyle();
InitToolBar();
ShowButtonList();
break;
case "BUTTONSAVE":
InitStyle();
InitToolBar();
DoButtonSave();
break;
default:
ShowStyleList();
break;
}}
function ShowPosition(){
echo "<table border=0 cellspacing=1 align=center class=navi>".
"<tr><th>".$GLOBALS["sPosition"]."</th></tr>".
"<tr><td align=center>[<a href='?'>所有样式列表</a>]&nbsp;&nbsp;&nbsp;&nbsp;[<a href='?action=styleadd'>新建一样式</a>]&nbsp;&nbsp;&nbsp;&nbsp;[<a href='#' onclick='history.back()'>返回前一页</a>]</td></tr>".
"</table><br>";
}
function ShowMessage($str){
echo "<table border=0 cellspacing=1 align=center class=list><tr><td>".$str."</td></tr></table><br>";
}
function ShowStyleList(){
ShowMessage("<b class=blue>以下为当前所有样式列表：</b>");
echo "<table border=0 cellpadding=0 cellspacing=1 class=list align=center>".
"<form action='?action=del' method=post name=myform>".
"<tr align=center>".
"<th width='10%'>样式名</th>".
"<th width='10%'>最佳宽度</th>".
"<th width='10%'>最佳高度</th>".
"<th width='45%'>说明</th>".
"<th width='25%'>管理</th>".
"</tr>";
for ($i=1;$i<=count($GLOBALS["aStyle"]);$i++){
$aCurrStyle = explode("|||", $GLOBALS["aStyle"][$i]);
$sManage = "<a href='?action=stylepreview&id=".$i."' target='_blank'>预览</a>|<a href='?action=code&id=".$i."'>代码</a>|<a href='?action=styleset&id=".$i."'>设置</a>|<a href='?action=toolbar&id=".$i."'>工具栏</a>|<a href='?action=copy&id=".$i."'>拷贝</a>|<a href='?action=styledel&id=".$i."' onclick=\"return confirm('提示：您确定要删除此样式吗？')\">删除</a>";
echo "<tr align=center>".
"<td>".HTMLEncode($aCurrStyle[0])."</td>".
"<td>".$aCurrStyle[4]."</td>".
"<td>".$aCurrStyle[5]."</td>".
"<td align=left>".HTMLEncode($aCurrStyle[26])."</td>".
"<td>".$sManage."</td>".
"</tr>";
}
echo "</table><br>";
ShowMessage("<b class=blue>提示：</b>你可以通过“拷贝”一样式以达到快速新建样式的目的。");
}
function DoCopy(){
$b = false;
$i = 0;
while ($b == false){
$i = $i + 1;
$sNewName = $GLOBALS["sStyleName"].$i;
if (StyleName2ID($sNewName) == -1) {
$b = true;
}}
$nNewStyleID = count($GLOBALS["aStyle"]) + 1;
$GLOBALS["aStyle"][$nNewStyleID] = $sNewName.substr($GLOBALS["aStyle"][$GLOBALS["nStyleID"]], strlen($GLOBALS["sStyleName"]));
$nToolbarNum = count($GLOBALS["aToolbar"]);
for ($i=1;$i<=$nToolbarNum;$i++){
$aCurrToolbar = explode("|||", $GLOBALS["aToolbar"][$i]);
if ($aCurrToolbar[0] == $GLOBALS["sStyleID"]) {
$nNewToolbarID = count($GLOBALS["aToolbar"]) + 1;
$GLOBALS["aToolbar"][$nNewToolbarID] = $nNewStyleID."|||".$aCurrToolbar[1]."|||".$aCurrToolbar[2]."|||".$aCurrToolbar[3];
}}
WriteConfig();
GoUrl("?");
}
function StyleName2ID($str){
for ($i=1;$i<=count($GLOBALS["aStyle"]);$i++){
$aTemp = explode("|||", $GLOBALS["aStyle"][$i]);
if (strtolower($aTemp[0]) == strtolower($str)){
return $i;
}}
return -1;
}
function ShowStyleForm($sFlag){
if ($sFlag == "ADD"){
$GLOBALS["sStyleID"] = "";
$GLOBALS["sStyleName"] = "";
$GLOBALS["sFixWidth"] = "";
$GLOBALS["sSkin"] = "office2003";
$GLOBALS["sStyleUploadDir"] = "uploadfile/";
$GLOBALS["sStyleBaseHref"] = "";
$GLOBALS["sStyleContentPath"] = "";
$GLOBALS["sStyleWidth"] = "550";
$GLOBALS["sStyleHeight"] = "350";
$GLOBALS["sStyleMemo"] = "";
$GLOBALS["nStyleIsSys"] = 0;
$s_Title = "新增样式";
$s_Action = "StyleAddSave";
$GLOBALS["sStyleFileExt"] = "rar|zip|exe|doc|xls|chm|hlp";
$GLOBALS["sStyleFlashExt"] = "swf";
$GLOBALS["sStyleImageExt"] = "gif|jpg|jpeg|bmp";
$GLOBALS["sStyleMediaExt"] = "rm|mp3|wav|mid|midi|ra|avi|mpg|mpeg|asf|asx|wma|mov";
$GLOBALS["sStyleRemoteExt"] = "gif|jpg|bmp";
$GLOBALS["sStyleFileSize"] = "500";
$GLOBALS["sStyleFlashSize"] = "100";
$GLOBALS["sStyleImageSize"] = "100";
$GLOBALS["sStyleMediaSize"] = "100";
$GLOBALS["sStyleRemoteSize"] = "100";
$GLOBALS["sStyleStateFlag"] = "1";
$GLOBALS["sSBCode"] = "1";
$GLOBALS["sSBEdit"] = "1";
$GLOBALS["sSBText"] = "1";
$GLOBALS["sSBView"] = "1";
$GLOBALS["sSBSize"] = "1";
$GLOBALS["sEnterMode"] = "1";
$GLOBALS["sAreaCssMode"] = "0";
$GLOBALS["sFileNameMode"] = "0";
$GLOBALS["sStyleAutoRemote"] = "1";
$GLOBALS["sStyleShowBorder"] = "0";
$GLOBALS["sAutoDetectLanguage"] = "1";
$GLOBALS["sDefaultLanguage"] = "zh-cn";
$GLOBALS["sStyleAllowBrowse"] = "0";
$GLOBALS["sStyleUploadObject"] = "0";
$GLOBALS["sStyleAutoDir"] = "";
$GLOBALS["sStyleDetectFromWord"] = "1";
$GLOBALS["sStyleInitMode"] = "EDIT";
$GLOBALS["sStyleBaseUrl"] = "1";
$GLOBALS["sSLTFlag"] = "0";
$GLOBALS["sSLTMode"] = "0";
$GLOBALS["sSLTCheckFlag"] = "0";
$GLOBALS["sSLTMinSize"] = "300";
$GLOBALS["sSLTOkSize"] = "120";
$GLOBALS["sSYWZFlag"] = "0";
$GLOBALS["sSYText"] = "版权所有...";
$GLOBALS["sSYFontColor"] = "000000";
$GLOBALS["sSYFontSize"] = "12";
$GLOBALS["sSYFontName"] = "";
$GLOBALS["sSYPicPath"] = "";
$GLOBALS["sSLTSYObject"] = "0";
$GLOBALS["sSLTSYExt"] = "bmp|jpg|jpeg|gif";
$GLOBALS["sSYWZMinWidth"] = "100";
$GLOBALS["sSYShadowColor"] = "FFFFFF";
$GLOBALS["sSYShadowOffset"] = "1";
$GLOBALS["sStyleLocalExt"] = "gif|jpg|bmp|wmz";
$GLOBALS["sStyleLocalSize"] = "100";
$GLOBALS["sSYWZMinHeight"] = "100";
$GLOBALS["sSYWZPosition"] = "1";
$GLOBALS["sSYWZTextWidth"] = "66";
$GLOBALS["sSYWZTextHeight"] = "17";
$GLOBALS["sSYWZPaddingH"] = "5";
$GLOBALS["sSYWZPaddingV"] = "5";
$GLOBALS["sSYTPFlag"] = "0";
$GLOBALS["sSYTPMinWidth"] = "100";
$GLOBALS["sSYTPMinHeight"] = "100";
$GLOBALS["sSYTPPosition"] = "1";
$GLOBALS["sSYTPPaddingH"] = "5";
$GLOBALS["sSYTPPaddingV"] = "5";
$GLOBALS["sSYTPImageWidth"] = "88";
$GLOBALS["sSYTPImageHeight"] = "31";
$GLOBALS["sSYTPOpacity"] = "1";
$GLOBALS["sAdvApiFlag"] = "0";
$GLOBALS["sEncryptKey"] = "";
$GLOBALS["sPaginationMode"] = "1";
$GLOBALS["sPaginationKey"] = "{page}";
$GLOBALS["sPaginationAutoFlag"] = "0";
$GLOBALS["sPaginationAutoNum"] = "2000";
$GLOBALS["sSpaceSize"] = "";
$GLOBALS["sMFUMode"] = "0";
$GLOBALS["sMFUBlockSize"] = "200";
$GLOBALS["sMFUEnable"] = "1";
$GLOBALS["sCodeFormat"] = "2";
$GLOBALS["sTB2Flag"] = "1";
$GLOBALS["sTB2Code"] = "1";
$GLOBALS["sTB2Edit"] = "1";
$GLOBALS["sTB2Text"] = "1";
$GLOBALS["sTB2View"] = "1";
$GLOBALS["sTB2Max"] = "1";
$GLOBALS["sShowBlock"] = "0";
$GLOBALS["sFileNameSameFix"] = "";
$GLOBALS["sAutoDirOrderFlag"] = "0";
$GLOBALS["sAutoTypeDirImage"] = "";
$GLOBALS["sAutoTypeDirFlash"] = "";
$GLOBALS["sAutoTypeDirMedia"] = "";
$GLOBALS["sAutoTypeDirAttach"] = "";
$GLOBALS["sAutoTypeDirRemote"] = "";
$GLOBALS["sAutoTypeDirLocal"] = "";
$GLOBALS["sWordImportInitMode"] = "1";
$GLOBALS["sWordImportAPI"] = "1";
$GLOBALS["sQuickFormatInitFontName"] = "";
$GLOBALS["sQuickFormatInitFontSize"] = "";
$GLOBALS["sUIMinHeight"] = "300";
$GLOBALS["sSYValidNormal"] = "1";
$GLOBALS["sSYValidLocal"] = "";
$GLOBALS["sSYValidRemote"] = "";
$GLOBALS["sAutoDoneWordPaste"] = "";
$GLOBALS["sAutoDoneExcelPaste"] = "";
$GLOBALS["sAutoDoneQuickFormat"] = "";
$GLOBALS["sAutoDonePasteOption"] = "";
$GLOBALS["sWordEqImport"] = "0";
$GLOBALS["sResMovePath"] = "";
$GLOBALS["sFileServerPath"] = "";
$GLOBALS["sUseProxy"] = "1";
$GLOBALS["sUseCookie"] = "1";
}else{
$GLOBALS["sStyleName"] = HTMLEncode($GLOBALS["sStyleName"]);
$GLOBALS["sFixWidth"] = HTMLEncode($GLOBALS["sFixWidth"]);
$GLOBALS["sSkin"] = HTMLEncode($GLOBALS["sSkin"]);
$GLOBALS["sStyleUploadDir"] = HTMLEncode($GLOBALS["sStyleUploadDir"]);
$GLOBALS["sStyleBaseHref"] = HTMLEncode($GLOBALS["sStyleBaseHref"]);
$GLOBALS["sStyleContentPath"] = HTMLEncode($GLOBALS["sStyleContentPath"]);
$GLOBALS["sStyleMemo"] = HTMLEncode($GLOBALS["sStyleMemo"]);
$GLOBALS["sSYText"] = HTMLEncode($GLOBALS["sSYText"]);
$GLOBALS["sSYFontColor"] = HTMLEncode($GLOBALS["sSYFontColor"]);
$GLOBALS["sSYFontSize"] = HTMLEncode($GLOBALS["sSYFontSize"]);
$GLOBALS["sSYFontName"] = HTMLEncode($GLOBALS["sSYFontName"]);
$GLOBALS["sSYPicPath"] = HTMLEncode($GLOBALS["sSYPicPath"]);
$GLOBALS["sResMovePath"] = HTMLEncode($GLOBALS["sResMovePath"]);
$GLOBALS["sFileServerPath"] = HTMLEncode($GLOBALS["sFileServerPath"]);
$s_Title = "设置样式";
$s_Action = "StyleSetSave";
}
$s_FormStateFlag = InitCheckBox("d_stateflag", "1", $GLOBALS["sStyleStateFlag"]);
$s_FormSBCode = InitCheckBox("d_sbcode", "1", $GLOBALS["sSBCode"]);
$s_FormSBEdit = InitCheckBox("d_sbedit", "1", $GLOBALS["sSBEdit"]);
$s_FormSBText = InitCheckBox("d_sbtext", "1", $GLOBALS["sSBText"]);
$s_FormSBView = InitCheckBox("d_sbview", "1", $GLOBALS["sSBView"]);
$s_FormSBSize = InitCheckBox("d_sbsize", "1", $GLOBALS["sSBSize"]);
$s_FormTB2Flag = InitCheckBox("d_tb2flag", "1", $GLOBALS["sTB2Flag"]);
$s_FormTB2Code = InitCheckBox("d_tb2code", "1", $GLOBALS["sTB2Code"]);
$s_FormTB2Edit = InitCheckBox("d_tb2edit", "1", $GLOBALS["sTB2Edit"]);
$s_FormTB2Text = InitCheckBox("d_tb2text", "1", $GLOBALS["sTB2Text"]);
$s_FormTB2View = InitCheckBox("d_tb2view", "1", $GLOBALS["sTB2View"]);
$s_FormTB2Max = InitCheckBox("d_tb2max", "1", $GLOBALS["sTB2Max"]);
$s_FormSYValidNormal = InitCheckBox("d_syvalidnormal", "1", $GLOBALS["sSYValidNormal"]);
$s_FormSYValidLocal = InitCheckBox("d_syvalidlocal", "1", $GLOBALS["sSYValidLocal"]);
$s_FormSYValidRemote = InitCheckBox("d_syvalidremote", "1", $GLOBALS["sSYValidRemote"]);
$s_FormAutoDoneWordPaste = InitCheckBox("d_autodonewordpaste", "1", $GLOBALS["sAutoDoneWordPaste"]);
$s_FormAutoDoneExcelPaste = InitCheckBox("d_autodoneexcelpaste", "1", $GLOBALS["sAutoDoneExcelPaste"]);
$s_FormAutoDoneQuickFormat = InitCheckBox("d_autodonequickformat", "1", $GLOBALS["sAutoDoneQuickFormat"]);
$s_FormAutoDonePasteOption = InitCheckBox("d_autodonepasteoption", "1", $GLOBALS["sAutoDonePasteOption"]);
$s_FormEnterMode = InitSelect("d_entermode", explode("|", "Enter输入<P>，Shift+Enter输入<BR>|Enter输入<BR>，Shift+Enter输入<P>"), explode("|", "1|2"), $GLOBALS["sEnterMode"], "", "");
$s_FormAreaCssMode = InitSelect("d_areacssmode", explode("|", "常规模式|Word导入模式"), explode("|", "0|1"), $GLOBALS["sAreaCssMode"], "", "");
$s_FormFileNameMode = InitSelect("d_filenamemode", explode("|", "所有：自动重命名|所有：原文件名|附件按原名，其它自动重命名"), explode("|", "0|1|2"), $GLOBALS["sFileNameMode"], "", "");
$s_FormAutoRemote = InitSelect("d_autoremote", explode("|", "自动上传|不自动上传"), explode("|", "1|0"), $GLOBALS["sStyleAutoRemote"], "", "");
$s_FormShowBorder = InitSelect("d_showborder", explode("|", "默认显示|默认不显示"), explode("|", "1|0"), $GLOBALS["sStyleShowBorder"], "", "");
$s_FormShowBlock = InitSelect("d_showblock", explode("|", "默认显示|默认不显示"), explode("|", "1|0"), $GLOBALS["sShowBlock"], "", "");
$s_FormWordEqImport = InitSelect("d_wordeqimport", explode("|", "启用|不启用"), explode("|", "1|0"), $GLOBALS["sWordEqImport"], "", "");
$s_FormUseProxy = InitSelect("d_useproxy", explode("|", "使用|不使用"), explode("|", "1|0"), $GLOBALS["sUseProxy"], "", "");
$s_FormUseCookie = InitSelect("d_usecookie", explode("|", "不需要|客户端Cookie|服务器端Cookie"), explode("|", "0|1|2"), $GLOBALS["sUseCookie"], "", "");
$s_FormAutoDetectLanguage = InitSelect("d_autodetectlanguage", explode("|", "自动检测|不自动检测"), explode("|", "1|0"), $GLOBALS["sAutoDetectLanguage"], "", "");
$s_FormDefaultLanguage = InitSelect("d_defaultlanguage", explode("|", "简体中文|繁体中文|英文|日语|西班牙语|俄语|德语|法语|意大利语|荷兰语|瑞典语|葡萄牙语|挪威语|丹麦语"), explode("|", "zh-cn|zh-tw|en|ja|es|ru|de|fr|it|nl|sv|pt|no|da"), $GLOBALS["sDefaultLanguage"], "", "");
$s_FormAllowBrowse = InitSelect("d_allowbrowse", explode("|", "是,开启|否,关闭"), explode("|", "1|0"), $GLOBALS["sStyleAllowBrowse"], "", "");
$s_FormMFUMode = InitSelect("d_mfumode", explode("|", "PHP自带环境|eWebEditorMFUServer COM组件"), explode("|", "0|1"), $GLOBALS["sMFUMode"], "", "");
$s_FormMFUEnable = InitSelect("d_mfuenable", explode("|", "是,开启|否,关闭"), explode("|", "1|0"), $GLOBALS["sMFUEnable"], "", "");
$s_FormCodeFormat = InitSelect("d_codeformat", explode("|", "关闭|启用:缩进1空格|启用:缩进2空格|启用:缩进3空格|启用:缩进4空格|启用:缩进5空格|启用:缩进6空格|启用:缩进7空格|启用:缩进8空格"), explode("|", "0|1|2|3|4|5|6|7|8"), $GLOBALS["sCodeFormat"], "", "");
$s_FormUploadObject = InitSelect("d_uploadobject", explode("|", "自带"), explode("|", "0"), $GLOBALS["sStyleUploadObject"], "", "");
$s_FormDetectFromWord = InitSelect("d_detectfromword", explode("|", "启用|启用,且强制纯文本粘贴|不启用|不启用,且强制纯文本粘贴"), explode("|", "1|2|0|3"), $GLOBALS["sStyleDetectFromWord"], "", "");
$s_FormInitMode = InitSelect("d_initmode", explode("|", "代码模式|编辑模式|文本模式|预览模式"), explode("|", "CODE|EDIT|TEXT|VIEW"), $GLOBALS["sStyleInitMode"], "", "");
$s_FormBaseUrl = InitSelect("d_baseurl", explode("|", "相对路径|绝对根路径|绝对全路径|站外绝对全路径"), explode("|", "0|1|2|3"), $GLOBALS["sStyleBaseUrl"], "", "");
$s_FormSLTFlag = InitSelect("d_sltflag", explode("|", "不使用|使用|模拟使用,不生成小图,改大图显示宽高"), explode("|", "0|1|2"), $GLOBALS["sSLTFlag"], "", "");
$s_FormSLTMode = InitSelect("d_sltmode", explode("|", "大小图:显示小图,链到大图|大小图:显示大图|只生成小图"), explode("|", "0|1|2"), $GLOBALS["sSLTMode"], "", "");
$s_FormSLTCheckFlag = InitSelect("d_sltcheckflag", explode("|", "宽|高|宽或高"), explode("|", "0|1|2"), $GLOBALS["sSLTCheckFlag"], "", "");
$s_FormSYWZFlag = InitSelect("d_sywzflag", explode("|", "不使用|使用|前台用户控制"), explode("|", "0|1|2"), $GLOBALS["sSYWZFlag"], "", "");
$s_FormSLTSYObject = InitSelect("d_sltsyobject", explode("|", "PHP GD2图形库"), explode("|", "0"), $GLOBALS["sSLTSYObject"], "", "");
$s_FormSYTPFlag = InitSelect("d_sytpflag", explode("|", "不使用|使用|前台用户控制"), explode("|", "0|1|2"), $GLOBALS["sSYTPFlag"], "", "");
$s_FormSYWZPosition = InitSelect("d_sywzposition", explode("|", "左上|左中|左下|中上|中中|中下|右上|右中|右下"), explode("|", "1|2|3|4|5|6|7|8|9"), $GLOBALS["sSYWZPosition"], "", "");
$s_FormSYTPPosition = InitSelect("d_sytpposition", explode("|", "左上|左中|左下|中上|中中|中下|右上|右中|右下"), explode("|", "1|2|3|4|5|6|7|8|9"), $GLOBALS["sSYTPPosition"], "", "");
$s_FormAdvApiFlag = InitSelect("d_advapiflag", explode("|", "禁用|启用一般接口(明文cusdir)|启用高级接口(Session安全)"), explode("|", "0|1|2"), $GLOBALS["sAdvApiFlag"], "", "");
$s_FormPaginationMode = InitSelect("d_paginationmode", explode("|", "不启用|启用：标准分页符|启用：自定义分页符"), explode("|", "0|1|2"), $GLOBALS["sPaginationMode"], "", "");
$s_FormPaginationAutoFlag = InitSelect("d_paginationautoflag", explode("|", "不启用|部分启用,内容中已有分页时不启用|完全启用,内容中已有的分页会被替换"), explode("|", "0|1|2"), $GLOBALS["sPaginationAutoFlag"], "", "");
$s_FormAutoDirOrderFlag = InitSelect("d_autodirorderflag", explode("|", "文件类型目录/年月日目录/|年月日目录/文件类型目录/"), explode("|", "0|1"), $GLOBALS["sAutoDirOrderFlag"], "", "");
$s_FormWordImportInitMode = InitSelect("d_wordimportinitmode", explode("|", "选择优化模式|全部清除模式"), explode("|", "1|2"), $GLOBALS["sWordImportInitMode"], "", "");
$s_FormWordImportAPI = InitSelect("d_wordimportapi", explode("|", "界面有选项，初始为[自动处理]|界面有选项，初始为[微软Office]|界面有选项，初始为[金山WPS]|界面无选项，固定为[自动处理]|界面无选项，固定为[微软Office]|界面无选项，固定为[金山WPS]"), explode("|", "0|1|2|10|11|12"), $GLOBALS["sWordImportAPI"], "", "");
echo "<table border=0 cellpadding=0 cellspacing=1 align=center class=form>".
"<form action='?action=".$s_Action."&id=".$GLOBALS["sStyleID"]."' method=post name=myform onsubmit='return checkStyleSetForm(this)'>".
"<tr><th colspan=4>&nbsp;&nbsp;".$s_Title."（鼠标移到输入框可看说明，带*号为必填项）</th></tr>".
"<tr><td width='15%'>样式名称：</td><td width='35%'><input type=text class=input size=20 name=d_name title='引用此样式的名字，不要加特殊符号' value=\"".$GLOBALS["sStyleName"]."\"> <span class=red>*</span></td><td width='15%'>初始模式：</td><td width='35%'>".$s_FormInitMode." <span class=red>*</span></td></tr>".
"<tr><td>限宽模式宽度：</td><td><input type=text class=input size=20 name=d_fixwidth title='留空表示不启用，可以填入如：500px' value=\"".$GLOBALS["sFixWidth"]."\"></td><td>界面皮肤目录：</td><td><input type=text class=input size=15 name=d_skin title='存放界面皮肤文件的目录名，必须在skin下' value=\"".$GLOBALS["sSkin"]."\"> <select size=1 id=d_skin_drop onchange='this.form.d_skin.value=this.value'><option>-系统自带-</option><option value='blue1'>blue1</option><option value='blue2'>blue2</option><option value='flat1'>flat1</option><option value='flat2'>flat2</option><option value='flat3'>flat3</option><option value='flat4'>flat4</option><option value='flat5'>flat5</option><option value='flat6'>flat6</option><option value='flat7'>flat7</option><option value='flat8'>flat8</option><option value='flat9'>flat9</option><option value='flat10'>flat10</option><option value='green1'>green1</option><option value='light1'>light1</option><option value='office2000'>office2000</option><option value='office2003'>office2003</option><option value='officexp'>officexp</option><option value='red1'>red1</option><option value='vista1'>vista1</option><option value='yellow1'>yellow1</option></select> <span class=red>*</span></td></tr>".
"<tr><td>最佳宽度：</td><td><input type=text class=input name=d_width size=20 title='最佳引用效果的宽度，数字型' value='".$GLOBALS["sStyleWidth"]."'> <span class=red>*</span></td><td>最佳高度：</td><td><input type=text class=input name=d_height size=20 title='最佳引用效果的高度，数字型' value='".$GLOBALS["sStyleHeight"]."'> <span class=red>*</span></td></tr>".
"<tr><td>显示状态栏及按钮：</td><td>".$s_FormStateFlag."状态栏 ".$s_FormSBCode."代码 ".$s_FormSBEdit."编辑 ".$s_FormSBText."文本 ".$s_FormSBView."预览 ".$s_FormSBSize."缩放<span class=red>*</span></td><td>高级粘贴自动检测：</td><td>".$s_FormDetectFromWord." <span class=red>*</span></td></tr>".
"<tr><td>非编辑模式工具栏：</td><td>".$s_FormTB2Flag."工具栏 ".$s_FormTB2Code."代码 ".$s_FormTB2Edit."编辑 ".$s_FormTB2Text."文本 ".$s_FormTB2View."预览 ".$s_FormTB2Max."最大化<span class=red>*</span></td><td>代码格式化：</td><td>".$s_FormCodeFormat." <span class=red>*</span></td></tr>".
"<tr><td>远程文件：</td><td>".$s_FormAutoRemote." <span class=red>*</span></td><td>显示表格虚框：</td><td>".$s_FormShowBorder." <span class=red>*</span></td></tr>".
"<tr><td>显示区块：</td><td>".$s_FormShowBlock." <span class=red>*</span></td><td>Word导入初始模式：</td><td>".$s_FormWordImportInitMode." <span class=red>*</span></td></tr>".
"<tr><td>一键排版初始值：</td><td>字体：<input type=text class=input name=d_quickformatinitfontname size=10 title='字体名称' value=\"".$GLOBALS["sQuickFormatInitFontName"]."\"> 字号：<input type=text class=input name=d_quickformatinitfontsize size=10 title='字体大小' value=\"".$GLOBALS["sQuickFormatInitFontSize"]."\"></td><td>界面最小高度：</td><td><input type=text class=input name=d_uiminheight size=20 title='数字型' value=\"".$GLOBALS["sUIMinHeight"]."\"> <span class=red>*</span></td></tr>".
"<tr><td>自动语言检测：</td><td>".$s_FormAutoDetectLanguage." <span class=red>*</span></td><td>默认语言：</td><td>".$s_FormDefaultLanguage." <span class=red>*</span></td></tr>".
"<tr><td>回车换行模式：</td><td>".$s_FormEnterMode." <span class=red>*</span></td><td>编辑区CSS模式：</td><td>".$s_FormAreaCssMode." <span class=red>*</span></td></tr>".
"<tr><td>高级接口状态：</td><td>".$s_FormAdvApiFlag." <span class=red>*</span></td><td>安全接口加密串：</td><td><input type=text class=input size=20 name=d_encryptkey title='启用高级Session安全接口时，需要加密串，只能是字母和数字，不能有特殊字符' value=\"".$GLOBALS["sEncryptKey"]."\"><input type=button value='随机' onclick='CreateRndEncryptKey()'></td></tr>".
"<tr><td>一键处理模块：</td><td>".$s_FormAutoDoneWordPaste."Word粘贴 ".$s_FormAutoDoneExcelPaste."Excel粘贴 ".$s_FormAutoDonePasteOption."选择性粘贴 ".$s_FormAutoDoneQuickFormat."一键排版</td><td>Word导入接口选项：</td><td> ".$s_FormWordImportAPI."</td></tr>".
"<tr><td>WordEQ数据导入：</td><td>".$s_FormWordEqImport." <span class=red>*</span></td><td>资源文件移动路径：</td><td><input type=text class=input size=20 name=d_resmovepath value=\"".$GLOBALS["sResMovePath"]."\"></td></tr>".
"<tr><td>使用系统代理设置：</td><td>".$s_FormUseProxy." <span class=red>*</span></td><td>交互验证：</td><td>".$s_FormUseCookie." <span class=red>*</span></td></tr>".
"<tr><td>备注说明：</td><td colspan=3><input type=text name=d_memo size=90 title='此样式的说明，更有利于调用' value=\"".$GLOBALS["sStyleMemo"]."\"></td></tr>".
"<tr><td colspan=4><span class=red>&nbsp;&nbsp;&nbsp;上传相关设置（相关设置说明详见用户手册）：</span></td></tr>".
"<tr><td>上传组件：</td><td>".$s_FormUploadObject." <span class=red>*</span></td><td></td><td></td></tr>".
"<tr><td>自动目录顺序：</td><td>".$s_FormAutoDirOrderFlag." <span class=red>*</span></td><td>年月日自动目录：</td><td><input type=text class=input size=18 name=d_autodir title='留空则不启用此功能，可用关键字：{yyyy}、{mm}、{dd}' value=\"".$GLOBALS["sStyleAutoDir"]."\"> <select size=1 id=d_autodir_drop onchange='this.form.d_autodir.value=this.value'><option>-常用格式选择-</option><option value=''>不启用</option><option value='{yyyy}/'>{yyyy}/</option><option value='{yyyy}/{mm}/'>{yyyy}/{mm}/</option><option value='{yyyy}/{mm}/{dd}/'>{yyyy}/{mm}/{dd}/</option><option value='{yyyy}/{mm}{dd}/'>{yyyy}/{mm}{dd}/</option><option value='{yyyy}{mm}/'>{yyyy}{mm}/</option><option value='{yyyy}{mm}/{dd}/'>{yyyy}{mm}/{dd}/</option><option value='{yyyy}{mm}{dd}/'>{yyyy}{mm}{dd}/</option></select></td></tr>".
"<tr><td>文件名保存模式：</td><td>".$s_FormFileNameMode." <span class=red>*</span></td><td>文件名同名处理：</td><td><input type=text class=input size=18 name=d_filenamesamefix title='留空则为替换已存在文件，可用关键字：{name}、{sn}、{time}' value=\"".$GLOBALS["sFileNameSameFix"]."\"> <select size=1 id=d_filenamesamefix_drop onchange='this.form.d_filenamesamefix.value=this.value'><option>-常用格式选择-</option><option value=''>替换已存在文件</option><option value='{name}-{sn}'>自动重命名：原名+序号</option><option value='{name}-{time}'>自动重命名：原名+自动时间</option></select></td></tr>".
"<tr><td>上传文件浏览：</td><td>".$s_FormAllowBrowse." <span class=red>*</span></td><td>批量上传功能启用：</td><td>".$s_FormMFUEnable." <span class=red>*</span></td></tr>".
"<tr><td>批量上传接口组件：</td><td>".$s_FormMFUMode." <span class=red>*</span></td><td>批量上传分块大小：</td><td><input type=text class=input size=20 name=d_mfublocksize title='数字型，单位KB' value=\"".$GLOBALS["sMFUBlockSize"]."\">KB <span class=red>*</span></td></tr>".
"<tr><td>路径模式：</td><td>".$s_FormBaseUrl." <span class=red>*</span> <a href='#baseurl'>说明</a></td><td>上传路径：</td><td><input type=text class=input size=35 name=d_uploaddir title='上传文件所存放路径，相对eWebEditor根目录文件的路径' value=\"".$GLOBALS["sStyleUploadDir"]."\"> <span class=red>*</span></td></tr>".
"<tr><td>显示路径：</td><td><input type=text class=input size=35 name=d_basehref title='显示内容页所存放路径，必须以&quot;/&quot;开头' value=\"".$GLOBALS["sStyleBaseHref"]."\"></td><td>内容路径：</td><td><input type=text class=input size=35 name=d_contentpath title='实际保存在内容中的路径，相对显示路径的路径，不能以&quot;/&quot;开头' value=\"".$GLOBALS["sStyleContentPath"]."\"></td></tr>".
"<tr><td>文件服务接口路径：</td><td><input type=text class=input size=35 name=d_fileserverpath title='' value=\"".$GLOBALS["sFileServerPath"]."\"></td><td>&nbsp;</td><td>&nbsp;</td></tr>".
"<tr><td colspan=4><span class=red>&nbsp;&nbsp;&nbsp;允许上传文件类型及文件大小设置（文件大小单位为KB，0表示不允许）：</span></td></tr>".
"<tr><td>总上传空间限制：</td><td><input type=text class=input name=d_spacesize size=20 title='数字型，单位MB，不限制请留空' value='".$GLOBALS["sSpaceSize"]."'>MB</td><td></td><td></td></tr>".
"<tr><td>图片类型：</td><td colspan=3>文件扩展名：<input type=text class=input name=d_imageext size=30 title='用于图片相关的上传' value='".$GLOBALS["sStyleImageExt"]."'>&nbsp;&nbsp; 文件大小限制：<input type=text class=input name=d_imagesize size=10 title='数字型，单位KB' value='".$GLOBALS["sStyleImageSize"]."'>KB&nbsp;&nbsp; 自动类型目录：<input type=text class=input name=d_autotypedirimage size=20 title='空表示不启用，格式如：image/' value=\"".$GLOBALS["sAutoTypeDirImage"]."\"></td></tr>".
"<tr><td>Flash类型：</td><td colspan=3>文件扩展名：<input type=text class=input name=d_flashext size=30 title='用于插入Flash动画' value='".$GLOBALS["sStyleFlashExt"]."'>&nbsp;&nbsp; 文件大小限制：<input type=text class=input name=d_flashsize size=10 title='数字型，单位KB' value='".$GLOBALS["sStyleFlashSize"]."'>KB&nbsp;&nbsp; 自动类型目录：<input type=text class=input name=d_autotypedirflash size=20 title='空表示不启用，格式如：flash/' value=\"".$GLOBALS["sAutoTypeDirFlash"]."\"></td></tr>".
"<tr><td>媒体类型：</td><td colspan=3>文件扩展名：<input type=text class=input name=d_mediaext size=30 title='用于插入媒体文件' value='".$GLOBALS["sStyleMediaExt"]."'>&nbsp;&nbsp; 文件大小限制：<input type=text class=input name=d_mediasize size=10 title='数字型，单位KB' value='".$GLOBALS["sStyleMediaSize"]."'>KB&nbsp;&nbsp; 自动类型目录：<input type=text class=input name=d_autotypedirmedia size=20 title='空表示不启用，格式如：media/' value=\"".$GLOBALS["sAutoTypeDirMedia"]."\"></td></tr>".
"<tr><td>附件类型：</td><td colspan=3>文件扩展名：<input type=text class=input name=d_fileext size=30 title='用于插入附件' value='".$GLOBALS["sStyleFileExt"]."'>&nbsp;&nbsp; 文件大小限制：<input type=text class=input name=d_filesize size=10 title='数字型，单位KB' value='".$GLOBALS["sStyleFileSize"]."'>KB&nbsp;&nbsp; 自动类型目录：<input type=text class=input name=d_autotypedirattach size=20 title='空表示不启用，格式如：attach/' value=\"".$GLOBALS["sAutoTypeDirAttach"]."\"></td></tr>".
"<tr><td>远程类型：</td><td colspan=3>文件扩展名：<input type=text class=input name=d_remoteext size=30 title='用于自动上传远程文件' value='".$GLOBALS["sStyleRemoteExt"]."'>&nbsp;&nbsp; 文件大小限制：<input type=text class=input name=d_remotesize size=10 title='数字型，单位KB' value='".$GLOBALS["sStyleRemoteSize"]."'>KB&nbsp;&nbsp; 自动类型目录：<input type=text class=input name=d_autotypedirremote size=20 title='空表示不启用，格式如：remote/' value=\"".$GLOBALS["sAutoTypeDirRemote"]."\"></td></tr>".
"<tr><td>本地类型：</td><td colspan=3>文件扩展名：<input type=text class=input name=d_localext size=30 title='用于自动上传本地文件' value='".$GLOBALS["sStyleLocalExt"]."'>&nbsp;&nbsp; 文件大小限制：<input type=text class=input name=d_localsize size=10 title='数字型，单位KB' value='".$GLOBALS["sStyleLocalSize"]."'>KB&nbsp;&nbsp; 自动类型目录：<input type=text class=input name=d_autotypedirlocal size=20 title='空表示不启用，格式如：local/' value=\"".$GLOBALS["sAutoTypeDirLocal"]."\"></td></tr>".	
"<tr><td colspan=4><span class=red>&nbsp;&nbsp;&nbsp;分页相关设置（前台显示页应作相应处理以识别分页符）：</span></td></tr>".
"<tr><td>分页符模式：</td><td>".$s_FormPaginationMode." <span class=red>*</span></td><td>自定义分页符关键字：</td><td><input type=text class=input size=20 name=d_paginationkey title='' value=\"".$GLOBALS["sPaginationKey"]."\"></td></tr>".
"<tr><td>提交内容自动分页：</td><td>".$s_FormPaginationAutoFlag." <span class=red>*</span></td><td>自动分页字数：</td><td><input type=text class=input size=20 name=d_paginationautonum title='当启用自动分页时，将依此值进行自动分页' value=\"".$GLOBALS["sPaginationAutoNum"]."\"></td></tr>".
"<tr><td colspan=4><span class=red>&nbsp;&nbsp;&nbsp;缩略图及水印相关设置：</span></td></tr>".
"<tr><td>图形处理组件：</td><td>".$s_FormSLTSYObject."</td><td>处理图形扩展名：</td><td><input type=text name=d_sltsyext size=20 class=input value=\"".$GLOBALS["sSLTSYExt"]."\"></td></tr>".
"<tr><td>缩略图使用状态：</td><td>".$s_FormSLTFlag."</td><td>缩略图生成模式：</td><td>".$s_FormSLTMode."</td></tr>".
"<tr><td>缩略图长度条件：</td><td>".$s_FormSLTCheckFlag."大于<input type=text name=d_sltminsize size=10 class=input title='图形的长度只有达到此最小长度要求时才会生成缩略图，数字型' value='".$GLOBALS["sSLTMinSize"]."'>px</td><td>缩略图生成长度：</td><td><input type=text name=d_sltoksize size=20 class=input title='生成的缩略图长度值，数字型' value='".$GLOBALS["sSLTOkSize"]."'>px</td></tr>".
"<tr><td>水印有效模块：</td><td>".$s_FormSYValidNormal."普通上传 ".$s_FormSYValidLocal."本地上传 ".$s_FormSYValidRemote."远程上传</td><td></td><td></td></tr>".
"<tr><td>文字水印使用状态：</td><td>".$s_FormSYWZFlag."</td><td>文字水印启用条件：</td><td>宽:<input type=text name=d_sywzminwidth size=4 class=input title='图形的宽度只有达到此最小宽度要求时才会生成水印，数字型' value='".$GLOBALS["sSYWZMinWidth"]."'>px&nbsp; 高:<input type=text name=d_sywzminheight size=4 class=input title='图形的高度只有达到此最小高度要求时才会生成水印，数字型' value='".$GLOBALS["sSYWZMinHeight"]."'>px</td></tr>".
"<tr><td>文字水印内容：</td><td><input type=text name=d_sytext size=20 class=input title='当使用文字水印时的文字内容' value=\"".$GLOBALS["sSYText"]."\"></td><td>文字水印字体颜色：</td><td><input type=text name=d_syfontcolor size=20 class=input title='当使用文字水印时文字的颜色' value=\"".$GLOBALS["sSYFontColor"]."\"></td></tr>".
"<tr><td>文字水印阴影颜色：</td><td><input type=text name=d_syshadowcolor size=20 class=input title='当使用文字水印时的文字阴影颜色' value=\"".$GLOBALS["sSYShadowColor"]."\"></td><td>文字水印阴影大小：</td><td><input type=text name=d_syshadowoffset size=20 class=input title='当使用文字水印时文字的阴影大小' value=\"".$GLOBALS["sSYShadowOffset"]."\">px</td></tr>".
"<tr><td>文字水印字体大小：</td><td><input type=text name=d_syfontsize size=20 class=input title='当使用文字水印时文字的字体大小' value=\"".$GLOBALS["sSYFontSize"]."\">px</td><td>中文字体库及路径：</td><td><input type=text name=d_syfontname size=20 class=input title='当使用中文字时，字体库的文件名' value=\"".$GLOBALS["sSYFontName"]."\"> <a href='#fontname'>说明</a></td></tr>".
"<tr><td>文字水印位置：</td><td>".$s_FormSYWZPosition."</td><td>文字水印边距：</td><td>左右:<input type=text name=d_sywzpaddingh size=4 class=input title='居左时作用为左边距，居右时作用为右边距，数字型' value='".$GLOBALS["sSYWZPaddingH"]."'>px&nbsp; 上下:<input type=text name=d_sywzpaddingv size=4 class=input title='居上时作用为上边距，居下时作用为下边柜，数字型' value='".$GLOBALS["sSYWZPaddingV"]."'>px</td></tr>".
"<tr><td>文字水印文字占位：</td><td>宽:<input type=text name=d_sywztextwidth size=4 class=input title='水印文字的占位宽度，由字数、字体大小等设置的效果确定，数字型' value='".$GLOBALS["sSYWZTextWidth"]."'>px&nbsp; 高:<input type=text name=d_sywztextheight size=4 class=input title='水印文字的占位高度，由字数、字体大小等设置的效果确定，数字型' value='".$GLOBALS["sSYWZTextHeight"]."'>px&nbsp; <input type=button value='检测宽高' onclick='doCheckWH(1)'></td><td></td><td></td></tr>".
"<tr><td>图片水印使用状态：</td><td>".$s_FormSYTPFlag."</td><td>图片水印启用条件：</td><td>宽:<input type=text name=d_sytpminwidth size=4 class=input title='图形的宽度只有达到此最小宽度要求时才会生成水印，数字型' value='".$GLOBALS["sSYTPMinWidth"]."'>px&nbsp; 高:<input type=text name=d_sytpminheight size=4 class=input title='图形的高度只有达到此最小高度要求时才会生成水印，数字型' value='".$GLOBALS["sSYTPMinHeight"]."'>px</td></tr>".
"<tr><td>图片水印位置：</td><td>".$s_FormSYTPPosition."</td><td>图片水印边距：</td><td>左右:<input type=text name=d_sytppaddingh size=4 class=input title='居左时作用为左边距，居右时作用为右边距，数字型' value='".$GLOBALS["sSYTPPaddingH"]."'>px&nbsp; 上下:<input type=text name=d_sytppaddingv size=4 class=input title='居上时作用为上边距，居下时作用为下边柜，数字型' value='".$GLOBALS["sSYTPPaddingV"]."'>px</td></tr>".
"<tr><td>图片水印图片路径：</td><td><input type=text name=d_sypicpath size=20 class=input title='当使用图片水印时图片的路径' value=\"".$GLOBALS["sSYPicPath"]."\"></td><td>图片水印透明度：</td><td><input type=text name=d_sytpopacity size=20 class=input title='0至1间的数字，如0.5表示半透明' value=\"".$GLOBALS["sSYTPOpacity"]."\"></td></tr>".
"<tr><td>图片水印图片占位：</td><td>宽:<input type=text name=d_sytpimagewidth size=4 class=input title='水印图片的宽度，数字型' value='".$GLOBALS["sSYTPImageWidth"]."'>px&nbsp; 高:<input type=text name=d_sytpimageheight size=4 class=input title='水印图片的高度，数字型' value='".$GLOBALS["sSYTPImageHeight"]."'>px&nbsp; <input type=button value='检测宽高' onclick='doCheckWH(2)'></td><td></td><td></td></tr>".
"<tr><td>水印宽高检测区：</td><td colspan=3><span id=tdPreview></span></td></tr>".
"<tr><td align=center colspan=4><input type=submit value='  提交  ' align=absmiddle>&nbsp;<input type=reset name=btnReset value='  重填  '></td></tr>".
"</form>".
"</table><br>";
$sMsg = "<a name=baseurl></a><p><span class=blue><b>路径模式设置说明：</b></span><br>".
"<b>相对路径：</b>指所有的相关上传或自动插入文件路径，编辑后都以\"UploadFile/...\"或\"../UploadFile/...\"形式呈现，当使用此模式时，显示路径和内容路径必填，显示路径必须以\"/\"开头和结尾，内容路径设置中不能以\"/\"开头。<br>".
"<b>绝对根路径：</b>指所有的相关上传或自动插入文件路径，编辑后都以\"/eWebEditor/UploadFile/...\"这种形式呈现，当使用此模式时，显示路径和内容路径不必填。<br>".
"<b>绝对全路径：</b>指所有的相关上传或自动插入文件路径，编辑后都以\"http://xxx.xxx.xxx/eWebEditor/UploadFile/...\"这种形式呈现，当使用此模式时，显示路径和内容路径不必填。<br>".
"<b>站外绝对全路径：</b>当使用此模式时，上传路径必须是实际物理路径，如：\"c:\\xxx\\\"；显示路径为空；内容路径必须以\"http\"开头。</p>".
"<a name=fontname></a><p><span class=blue><b>中文字体库及路径设置说明：</b></span><br>".
"当使用中文文字水印时必填一个字库，使用英文水印时为提高效率请留空，如设为“simkai.ttf”，则请把此字体库文件拷贝到编辑器的php目录。</p>";
ShowMessage($sMsg);
}
function InitStyle(){
global $sStyleID, $sStyleName, $sFixWidth, $sSkin, $sStyleUploadDir, $sStyleWidth, $sStyleHeight, $sStyleMemo, $nStyleIsSys, $sStyleStateFlag, $sStyleDetectFromWord, $sStyleInitMode, $sStyleBaseUrl, $sStyleUploadObject, $sStyleAutoDir, $sStyleBaseHref, $sStyleContentPath, $sStyleAutoRemote, $sStyleShowBorder, $sAutoDetectLanguage, $sDefaultLanguage, $sStyleAllowBrowse;
global $sSLTFlag, $sSLTMode, $sSLTCheckFlag, $sSLTMinSize, $sSLTOkSize, $sSYWZFlag, $sSYText, $sSYFontColor, $sSYFontSize, $sSYFontName, $sSYPicPath, $sSLTSYObject, $sSLTSYExt, $sSYWZMinWidth, $sSYShadowColor, $sSYShadowOffset, $sSYWZMinHeight, $sSYWZPosition, $sSYWZTextWidth, $sSYWZTextHeight, $sSYWZPaddingH, $sSYWZPaddingV, $sSYTPFlag, $sSYTPMinWidth, $sSYTPMinHeight, $sSYTPPosition, $sSYTPPaddingH, $sSYTPPaddingV, $sSYTPImageWidth, $sSYTPImageHeight, $sSYTPOpacity, $sAdvApiFlag;
global $sStyleFileExt, $sStyleFlashExt, $sStyleImageExt, $sStyleMediaExt, $sStyleRemoteExt, $sStyleLocalExt, $sStyleFileSize, $sStyleFlashSize, $sStyleImageSize, $sStyleMediaSize, $sStyleRemoteSize, $sStyleLocalSize;
global $sToolBarID, $sToolBarName, $sToolBarOrder, $sToolBarButton;
global $sSBCode, $sSBEdit, $sSBText, $sSBView, $sSBSize;
global $sEnterMode, $sAreaCssMode, $sFileNameMode, $sEncryptKey;
global $sPaginationMode, $sPaginationKey, $sPaginationAutoFlag, $sPaginationAutoNum, $sSpaceSize;
global $sMFUMode, $sMFUBlockSize, $sMFUEnable;
global $sCodeFormat, $sTB2Flag, $sTB2Code, $sTB2Edit, $sTB2Text, $sTB2View, $sTB2Max, $sShowBlock;
global $sFileNameSameFix, $sAutoDirOrderFlag, $sAutoTypeDirImage, $sAutoTypeDirFlash, $sAutoTypeDirMedia, $sAutoTypeDirAttach, $sAutoTypeDirRemote, $sAutoTypeDirLocal;
global $sWordImportInitMode, $sWordImportAPI, $sQuickFormatInitFontName, $sQuickFormatInitFontSize;
global $sUIMinHeight, $sSYValidNormal, $sSYValidLocal, $sSYValidRemote;
global $sAutoDoneWordPaste, $sAutoDoneExcelPaste, $sAutoDoneQuickFormat, $sAutoDonePasteOption;
global $sWordEqImport, $sResMovePath, $sFileServerPath, $sUseProxy, $sUseCookie;
global $nStyleID;
$b = false;
$sStyleID = TrimGet("id");
if (is_numeric($sStyleID)) {
$nStyleID = (int)($sStyleID);
if ($nStyleID <= count($GLOBALS["aStyle"])) {
$aCurrStyle = explode("|||", $GLOBALS["aStyle"][$nStyleID]);
$sStyleName = $aCurrStyle[0];
$sFixWidth = $aCurrStyle[1];
$sSkin = $aCurrStyle[2];
$sStyleUploadDir = $aCurrStyle[3];
$sStyleWidth = $aCurrStyle[4];
$sStyleHeight = $aCurrStyle[5];
$sStyleFileExt = $aCurrStyle[6];
$sStyleFlashExt = $aCurrStyle[7];
$sStyleImageExt = $aCurrStyle[8];
$sStyleMediaExt = $aCurrStyle[9];
$sStyleRemoteExt = $aCurrStyle[10];
$sStyleFileSize = $aCurrStyle[11];
$sStyleFlashSize = $aCurrStyle[12];
$sStyleImageSize = $aCurrStyle[13];
$sStyleMediaSize = $aCurrStyle[14];
$sStyleRemoteSize = $aCurrStyle[15];
$sStyleStateFlag = $aCurrStyle[16];
$sStyleDetectFromWord = $aCurrStyle[17];
$sStyleInitMode = $aCurrStyle[18];
$sStyleBaseUrl = $aCurrStyle[19];
$sStyleUploadObject = $aCurrStyle[20];
$sStyleBaseHref = $aCurrStyle[22];
$sStyleContentPath = $aCurrStyle[23];
$sStyleAutoRemote = $aCurrStyle[24];
$sStyleShowBorder = $aCurrStyle[25];
$sStyleMemo = $aCurrStyle[26];
$sAutoDetectLanguage = $aCurrStyle[27];
$sDefaultLanguage = $aCurrStyle[28];
$sSLTFlag = $aCurrStyle[29];
$sSLTMinSize = $aCurrStyle[30];
$sSLTOkSize = $aCurrStyle[31];
$sSYWZFlag = $aCurrStyle[32];
$sSYText = $aCurrStyle[33];
$sSYFontColor = $aCurrStyle[34];
$sSYFontSize = $aCurrStyle[35];
$sSYFontName = $aCurrStyle[36];
$sSYPicPath = $aCurrStyle[37];
$sSLTSYObject = $aCurrStyle[38];
$sSLTSYExt = $aCurrStyle[39];
$sSYWZMinWidth = $aCurrStyle[40];
$sSYShadowColor = $aCurrStyle[41];
$sSYShadowOffset = $aCurrStyle[42];
$sStyleAllowBrowse = $aCurrStyle[43];
$sStyleLocalExt = $aCurrStyle[44];
$sStyleLocalSize = $aCurrStyle[45];
$sSYWZMinHeight = $aCurrStyle[46];
$sSYWZPosition = $aCurrStyle[47];
$sSYWZTextWidth = $aCurrStyle[48];
$sSYWZTextHeight = $aCurrStyle[49];
$sSYWZPaddingH = $aCurrStyle[50];
$sSYWZPaddingV = $aCurrStyle[51];
$sSYTPFlag = $aCurrStyle[52];
$sSYTPMinWidth = $aCurrStyle[53];
$sSYTPMinHeight = $aCurrStyle[54];
$sSYTPPosition = $aCurrStyle[55];
$sSYTPPaddingH = $aCurrStyle[56];
$sSYTPPaddingV = $aCurrStyle[57];
$sSYTPImageWidth = $aCurrStyle[58];
$sSYTPImageHeight = $aCurrStyle[59];
$sSYTPOpacity = $aCurrStyle[60];
$sAdvApiFlag = $aCurrStyle[61];
$sSBCode = $aCurrStyle[62];
$sSBEdit = $aCurrStyle[63];
$sSBText = $aCurrStyle[64];
$sSBView = $aCurrStyle[65];
$sEnterMode = $aCurrStyle[66];
$sAreaCssMode = $aCurrStyle[67];
$sFileNameMode = $aCurrStyle[68];
$sSLTMode = $aCurrStyle[69];
$sEncryptKey = $aCurrStyle[70];
$sStyleAutoDir = $aCurrStyle[71];
$sPaginationMode = $aCurrStyle[72];
$sPaginationKey = $aCurrStyle[73];
$sPaginationAutoFlag = $aCurrStyle[74];
$sPaginationAutoNum = $aCurrStyle[75];
$sSBSize = $aCurrStyle[76];
$sSLTCheckFlag = $aCurrStyle[77];
$sSpaceSize = $aCurrStyle[78];
$sMFUMode = $aCurrStyle[79];
$sMFUBlockSize = $aCurrStyle[80];
$sMFUEnable = $aCurrStyle[81];
$sCodeFormat = $aCurrStyle[82];
$sTB2Flag = $aCurrStyle[83];
$sTB2Code = $aCurrStyle[84];
$sTB2Max = $aCurrStyle[85];
$sShowBlock = $aCurrStyle[86];
$sFileNameSameFix = $aCurrStyle[87];
$sAutoDirOrderFlag = $aCurrStyle[88];
$sAutoTypeDirImage = $aCurrStyle[89];
$sAutoTypeDirFlash = $aCurrStyle[90];
$sAutoTypeDirMedia = $aCurrStyle[91];
$sAutoTypeDirAttach = $aCurrStyle[92];
$sAutoTypeDirRemote = $aCurrStyle[93];
$sAutoTypeDirLocal = $aCurrStyle[94];
$sWordImportInitMode = $aCurrStyle[95];
$sQuickFormatInitFontName = $aCurrStyle[96];
$sQuickFormatInitFontSize = $aCurrStyle[97];
$sUIMinHeight = $aCurrStyle[98];
$sSYValidNormal = $aCurrStyle[99];
$sSYValidLocal = $aCurrStyle[100];
$sSYValidRemote = $aCurrStyle[101];
$sAutoDoneWordPaste = $aCurrStyle[102];
$sAutoDoneExcelPaste = $aCurrStyle[103];
$sAutoDoneQuickFormat = $aCurrStyle[104];
$sWordImportAPI = $aCurrStyle[105];
$sAutoDonePasteOption = $aCurrStyle[106];
$sTB2Edit = $aCurrStyle[107];
$sTB2Text = $aCurrStyle[108];
$sTB2View = $aCurrStyle[109];
$sWordEqImport = $aCurrStyle[110];
$sResMovePath = $aCurrStyle[111];
$sFileServerPath = $aCurrStyle[112];
$sUseProxy = $aCurrStyle[113];
$sUseCookie = $aCurrStyle[114];
$b = true;
}}
if ($b == false) {
GoError("无效的样式ID号，请通过页面上的链接进行操作！");
}}
function CheckStyleForm(){
$GLOBALS["sStyleName"] = TrimGet("d_name");
$GLOBALS["sFixWidth"] = TrimGet("d_fixwidth");
$GLOBALS["sSkin"] = TrimGet("d_skin");
$GLOBALS["sStyleUploadDir"] = TrimGet("d_uploaddir");
$GLOBALS["sStyleWidth"] = TrimGet("d_width");
$GLOBALS["sStyleHeight"] = TrimGet("d_height");
$GLOBALS["sStyleFileExt"] = TrimGet("d_fileext");
$GLOBALS["sStyleFlashExt"] = TrimGet("d_flashext");
$GLOBALS["sStyleImageExt"] = TrimGet("d_imageext");
$GLOBALS["sStyleMediaExt"] = TrimGet("d_mediaext");
$GLOBALS["sStyleRemoteExt"] = TrimGet("d_remoteext");
$GLOBALS["sStyleFileSize"] = TrimGet("d_filesize");
$GLOBALS["sStyleFlashSize"] = TrimGet("d_flashsize");
$GLOBALS["sStyleImageSize"] = TrimGet("d_imagesize");
$GLOBALS["sStyleMediaSize"] = TrimGet("d_mediasize");
$GLOBALS["sStyleRemoteSize"] = TrimGet("d_remotesize");
$GLOBALS["sStyleStateFlag"] = TrimGet("d_stateflag");
$GLOBALS["sStyleDetectFromWord"] = TrimGet("d_detectfromword");
$GLOBALS["sStyleInitMode"] = TrimGet("d_initmode");
$GLOBALS["sStyleBaseUrl"] = TrimGet("d_baseurl");
$GLOBALS["sStyleUploadObject"] = TrimGet("d_uploadobject");
$GLOBALS["sStyleBaseHref"] = TrimGet("d_basehref");
$GLOBALS["sStyleContentPath"] = TrimGet("d_contentpath");
$GLOBALS["sStyleAutoRemote"] = TrimGet("d_autoremote");
$GLOBALS["sStyleShowBorder"] = TrimGet("d_showborder");
$GLOBALS["sStyleMemo"] = TrimGet("d_memo");
$GLOBALS["sAutoDetectLanguage"] = TrimGet("d_autodetectlanguage");
$GLOBALS["sDefaultLanguage"] = TrimGet("d_defaultlanguage");
$GLOBALS["sSLTFlag"] = TrimGet("d_sltflag");
$GLOBALS["sSLTMinSize"] = TrimGet("d_sltminsize");
$GLOBALS["sSLTOkSize"] = TrimGet("d_sltoksize");
$GLOBALS["sSYWZFlag"] = TrimGet("d_sywzflag");
$GLOBALS["sSYText"] = TrimGet("d_sytext");
$GLOBALS["sSYFontColor"] = TrimGet("d_syfontcolor");
$GLOBALS["sSYFontSize"] = TrimGet("d_syfontsize");
$GLOBALS["sSYFontName"] = TrimGet("d_syfontname");
$GLOBALS["sSYPicPath"] = TrimGet("d_sypicpath");
$GLOBALS["sSLTSYObject"] = TrimGet("d_sltsyobject");
$GLOBALS["sSLTSYExt"] = TrimGet("d_sltsyext");
$GLOBALS["sSYWZMinWidth"] = TrimGet("d_sywzminwidth");
$GLOBALS["sSYShadowColor"] = TrimGet("d_syshadowcolor");
$GLOBALS["sSYShadowOffset"] = TrimGet("d_syshadowoffset");
$GLOBALS["sStyleAllowBrowse"] = TrimGet("d_allowbrowse");
$GLOBALS["sStyleLocalExt"] = TrimGet("d_localext");
$GLOBALS["sStyleLocalSize"] = TrimGet("d_localsize");
$GLOBALS["sSYWZMinHeight"] = TrimGet("d_sywzminheight");
$GLOBALS["sSYWZPosition"] = TrimGet("d_sywzposition");
$GLOBALS["sSYWZTextWidth"] = TrimGet("d_sywztextwidth");
$GLOBALS["sSYWZTextHeight"] = TrimGet("d_sywztextheight");
$GLOBALS["sSYWZPaddingH"] = TrimGet("d_sywzpaddingh");
$GLOBALS["sSYWZPaddingV"] = TrimGet("d_sywzpaddingv");
$GLOBALS["sSYTPFlag"] = TrimGet("d_sytpflag");
$GLOBALS["sSYTPMinWidth"] = TrimGet("d_sytpminwidth");
$GLOBALS["sSYTPMinHeight"] = TrimGet("d_sytpminheight");
$GLOBALS["sSYTPPosition"] = TrimGet("d_sytpposition");
$GLOBALS["sSYTPPaddingH"] = TrimGet("d_sytppaddingh");
$GLOBALS["sSYTPPaddingV"] = TrimGet("d_sytppaddingv");
$GLOBALS["sSYTPImageWidth"] = TrimGet("d_sytpimagewidth");
$GLOBALS["sSYTPImageHeight"] = TrimGet("d_sytpimageheight");
$GLOBALS["sSYTPOpacity"] = TrimGet("d_sytpopacity");
$GLOBALS["sAdvApiFlag"] = TrimGet("d_advapiflag");
$GLOBALS["sSBCode"] = TrimGet("d_sbcode");
$GLOBALS["sSBEdit"] = TrimGet("d_sbedit");
$GLOBALS["sSBText"] = TrimGet("d_sbtext");
$GLOBALS["sSBView"] = TrimGet("d_sbview");
$GLOBALS["sEnterMode"] = TrimGet("d_entermode");
$GLOBALS["sAreaCssMode"] = TrimGet("d_areacssmode");
$GLOBALS["sFileNameMode"] = TrimGet("d_filenamemode");
$GLOBALS["sSLTMode"] = TrimGet("d_sltmode");
$GLOBALS["sEncryptKey"] = TrimGet("d_encryptkey");
$GLOBALS["sStyleAutoDir"] = TrimGet("d_autodir");
$GLOBALS["sPaginationMode"] = TrimGet("d_paginationmode");
$GLOBALS["sPaginationKey"] = TrimGet("d_paginationkey");
$GLOBALS["sPaginationAutoFlag"] = TrimGet("d_paginationautoflag");
$GLOBALS["sPaginationAutoNum"] = TrimGet("d_paginationautonum");
$GLOBALS["sSBSize"] = TrimGet("d_sbsize");
$GLOBALS["sSLTCheckFlag"] = TrimGet("d_sltcheckflag");
$GLOBALS["sSpaceSize"] = TrimGet("d_spacesize");
$GLOBALS["sMFUMode"] = TrimGet("d_mfumode");
$GLOBALS["sMFUBlockSize"] = TrimGet("d_mfublocksize");
$GLOBALS["sMFUEnable"] = TrimGet("d_mfuenable");
$GLOBALS["sCodeFormat"] = TrimGet("d_codeformat");
$GLOBALS["sTB2Flag"] = TrimGet("d_tb2flag");
$GLOBALS["sTB2Code"] = TrimGet("d_tb2code");
$GLOBALS["sTB2Edit"] = TrimGet("d_tb2edit");
$GLOBALS["sTB2Text"] = TrimGet("d_tb2text");
$GLOBALS["sTB2View"] = TrimGet("d_tb2view");
$GLOBALS["sTB2Max"] = TrimGet("d_tb2max");
$GLOBALS["sShowBlock"] = TrimGet("d_showblock");
$GLOBALS["sFileNameSameFix"] = TrimGet("d_filenamesamefix");
$GLOBALS["sAutoDirOrderFlag"] = TrimGet("d_autodirorderflag");
$GLOBALS["sAutoTypeDirImage"] = TrimGet("d_autotypedirimage");
$GLOBALS["sAutoTypeDirFlash"] = TrimGet("d_autotypedirflash");
$GLOBALS["sAutoTypeDirMedia"] = TrimGet("d_autotypedirmedia");
$GLOBALS["sAutoTypeDirAttach"] = TrimGet("d_autotypedirattach");
$GLOBALS["sAutoTypeDirRemote"] = TrimGet("d_autotypedirremote");
$GLOBALS["sAutoTypeDirLocal"] = TrimGet("d_autotypedirlocal");
$GLOBALS["sWordImportInitMode"] = TrimGet("d_wordimportinitmode");
$GLOBALS["sWordImportAPI"] = TrimGet("d_wordimportapi");
$GLOBALS["sQuickFormatInitFontName"] = TrimGet("d_quickformatinitfontname");
$GLOBALS["sQuickFormatInitFontSize"] = TrimGet("d_quickformatinitfontsize");
$GLOBALS["sUIMinHeight"] = TrimGet("d_uiminheight");
$GLOBALS["sSYValidNormal"] = TrimGet("d_syvalidnormal");
$GLOBALS["sSYValidLocal"] = TrimGet("d_syvalidlocal");
$GLOBALS["sSYValidRemote"] = TrimGet("d_syvalidremote");
$GLOBALS["sAutoDoneWordPaste"] = TrimGet("d_autodonewordpaste");
$GLOBALS["sAutoDoneExcelPaste"] = TrimGet("d_autodoneexcelpaste");
$GLOBALS["sAutoDoneQuickFormat"] = TrimGet("d_autodonequickformat");
$GLOBALS["sAutoDonePasteOption"] = TrimGet("d_autodonepasteoption");
$GLOBALS["sWordEqImport"] = TrimGet("d_wordeqimport");
$GLOBALS["sResMovePath"] = TrimGet("d_resmovepath");
$GLOBALS["sFileServerPath"] = TrimGet("d_fileserverpath");
$GLOBALS["sUseProxy"] = TrimGet("d_useproxy");
$GLOBALS["sUseCookie"] = TrimGet("d_usecookie");
}
function DoStyleAddSave(){
if (StyleName2ID($GLOBALS["sStyleName"]) != -1){
GoError("此样式名已经存在，请用另一个样式名！");
}
$nNewStyleID = count($GLOBALS["aStyle"]) + 1;
$GLOBALS["aStyle"][$nNewStyleID] = GetStyleDataString();
WriteConfig();
ShowMessage("<b><span class=red>样式增加成功！</span></b><li><a href='?action=toolbar&id=".$nNewStyleID."'>设置此样式下的工具栏</a>");
}
function DoStyleSetSave(){
$GLOBALS["sStyleID"] = TrimGet("id");
if (is_numeric($GLOBALS["sStyleID"])) {
$n = StyleName2ID($GLOBALS["sStyleName"]);
if ((($n) != (int)$GLOBALS["sStyleID"]) && ($n != -1)) {
GoError("此样式名已经存在，请用另一个样式名！");
}
if (((int)($GLOBALS["sStyleID"]) < 1) && ((int)($GLOBALS["sStyleID"])>count($GLOBALS["aStyle"]))) {
GoError("无效的样式ID号，请通过页面上的链接进行操作！");
}
$aTemp = explode("|||", $GLOBALS["aStyle"][$GLOBALS["sStyleID"]]);
$s_OldStyleName = $aTemp[0];
$GLOBALS["aStyle"][$GLOBALS["sStyleID"]] = GetStyleDataString();
}else{
GoError("无效的样式ID号，请通过页面上的链接进行操作！");
}
WriteConfig();
ShowMessage("<b><span class=red>样式修改成功！</span></b><li><a href='?action=stylepreview&id=".$GLOBALS["sStyleID"]."' target='_blank'>预览此样式</a><li><a href='?action=toolbar&id=".$GLOBALS["sStyleID"]."'>设置此样式下的工具栏</a><li><a href='?action=styleset&id=".$GLOBALS["sStyleID"]."'>重新设置此样式</a>");
}
function GetStyleDataString(){
return ($GLOBALS["sStyleName"]."|||".$GLOBALS["sFixWidth"]."|||".$GLOBALS["sSkin"]."|||".$GLOBALS["sStyleUploadDir"]."|||".$GLOBALS["sStyleWidth"]."|||".$GLOBALS["sStyleHeight"]."|||".$GLOBALS["sStyleFileExt"]."|||".$GLOBALS["sStyleFlashExt"]."|||".$GLOBALS["sStyleImageExt"]."|||".$GLOBALS["sStyleMediaExt"]."|||".$GLOBALS["sStyleRemoteExt"]."|||".$GLOBALS["sStyleFileSize"]."|||".$GLOBALS["sStyleFlashSize"]."|||".$GLOBALS["sStyleImageSize"]."|||".$GLOBALS["sStyleMediaSize"]."|||".$GLOBALS["sStyleRemoteSize"]."|||".$GLOBALS["sStyleStateFlag"]."|||".$GLOBALS["sStyleDetectFromWord"]."|||".$GLOBALS["sStyleInitMode"]."|||".$GLOBALS["sStyleBaseUrl"]."|||".$GLOBALS["sStyleUploadObject"]."||||||".$GLOBALS["sStyleBaseHref"]."|||".$GLOBALS["sStyleContentPath"]."|||".$GLOBALS["sStyleAutoRemote"]."|||".$GLOBALS["sStyleShowBorder"]."|||".$GLOBALS["sStyleMemo"]."|||".$GLOBALS["sAutoDetectLanguage"]."|||".$GLOBALS["sDefaultLanguage"]."|||".$GLOBALS["sSLTFlag"]."|||".$GLOBALS["sSLTMinSize"]."|||".$GLOBALS["sSLTOkSize"]."|||".$GLOBALS["sSYWZFlag"]."|||".$GLOBALS["sSYText"]."|||".$GLOBALS["sSYFontColor"]."|||".$GLOBALS["sSYFontSize"]."|||".$GLOBALS["sSYFontName"]."|||".$GLOBALS["sSYPicPath"]."|||".$GLOBALS["sSLTSYObject"]."|||".$GLOBALS["sSLTSYExt"]."|||".$GLOBALS["sSYWZMinWidth"]."|||".$GLOBALS["sSYShadowColor"]."|||".$GLOBALS["sSYShadowOffset"]."|||".$GLOBALS["sStyleAllowBrowse"]."|||".$GLOBALS["sStyleLocalExt"]."|||".$GLOBALS["sStyleLocalSize"]."|||".$GLOBALS["sSYWZMinHeight"]."|||".$GLOBALS["sSYWZPosition"]."|||".$GLOBALS["sSYWZTextWidth"]."|||".$GLOBALS["sSYWZTextHeight"]."|||".$GLOBALS["sSYWZPaddingH"]."|||".$GLOBALS["sSYWZPaddingV"]."|||".$GLOBALS["sSYTPFlag"]."|||".$GLOBALS["sSYTPMinWidth"]."|||".$GLOBALS["sSYTPMinHeight"]."|||".$GLOBALS["sSYTPPosition"]."|||".$GLOBALS["sSYTPPaddingH"]."|||".$GLOBALS["sSYTPPaddingV"]."|||".$GLOBALS["sSYTPImageWidth"]."|||".$GLOBALS["sSYTPImageHeight"]."|||".$GLOBALS["sSYTPOpacity"]."|||".$GLOBALS["sAdvApiFlag"]."|||".$GLOBALS["sSBCode"]."|||".$GLOBALS["sSBEdit"]."|||".$GLOBALS["sSBText"]."|||".$GLOBALS["sSBView"]."|||".$GLOBALS["sEnterMode"]."|||".$GLOBALS["sAreaCssMode"]."|||".$GLOBALS["sFileNameMode"]."|||".$GLOBALS["sSLTMode"]."|||".$GLOBALS["sEncryptKey"]."|||".$GLOBALS["sStyleAutoDir"]."|||".$GLOBALS["sPaginationMode"]."|||".$GLOBALS["sPaginationKey"]."|||".$GLOBALS["sPaginationAutoFlag"]."|||".$GLOBALS["sPaginationAutoNum"]."|||".$GLOBALS["sSBSize"]."|||".$GLOBALS["sSLTCheckFlag"]."|||".$GLOBALS["sSpaceSize"]."|||".$GLOBALS["sMFUMode"]."|||".$GLOBALS["sMFUBlockSize"]."|||".$GLOBALS["sMFUEnable"]."|||".$GLOBALS["sCodeFormat"]."|||".$GLOBALS["sTB2Flag"]."|||".$GLOBALS["sTB2Code"]."|||".$GLOBALS["sTB2Max"]."|||".$GLOBALS["sShowBlock"]."|||".$GLOBALS["sFileNameSameFix"]."|||".$GLOBALS["sAutoDirOrderFlag"]."|||".$GLOBALS["sAutoTypeDirImage"]."|||".$GLOBALS["sAutoTypeDirFlash"]."|||".$GLOBALS["sAutoTypeDirMedia"]."|||".$GLOBALS["sAutoTypeDirAttach"]."|||".$GLOBALS["sAutoTypeDirRemote"]."|||".$GLOBALS["sAutoTypeDirLocal"]."|||".$GLOBALS["sWordImportInitMode"]."|||".$GLOBALS["sQuickFormatInitFontName"]."|||".$GLOBALS["sQuickFormatInitFontSize"]."|||".$GLOBALS["sUIMinHeight"]."|||".$GLOBALS["sSYValidNormal"]."|||".$GLOBALS["sSYValidLocal"]."|||".$GLOBALS["sSYValidRemote"]."|||".$GLOBALS["sAutoDoneWordPaste"]."|||".$GLOBALS["sAutoDoneExcelPaste"]."|||".$GLOBALS["sAutoDoneQuickFormat"]."|||".$GLOBALS["sWordImportAPI"]."|||".$GLOBALS["sAutoDonePasteOption"]."|||".$GLOBALS["sTB2Edit"]."|||".$GLOBALS["sTB2Text"]."|||".$GLOBALS["sTB2View"]."|||".$GLOBALS["sWordEqImport"]."|||".$GLOBALS["sResMovePath"]."|||".$GLOBALS["sFileServerPath"]."|||".$GLOBALS["sUseProxy"]."|||".$GLOBALS["sUseCookie"]);
}
function DoStyleDel(){
$GLOBALS["aStyle"][$GLOBALS["sStyleID"]] = "";
WriteConfig();
GoUrl("?");
}
function ShowStylePreview(){
echo "<html><head><meta http-equiv='X-UA-Compatible' content='IE=EmulateIE7'>".
"<title>样式预览</title>".
"<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>".
"</head><body>".
"<input type=hidden name=content1  value=''>".
"<iframe ID='eWebEditor1' src='../ewebeditor.htm?id=content1&style=".$GLOBALS["sStyleName"]."' frameborder=0 scrolling=no width='".$GLOBALS["sStyleWidth"]."' HEIGHT='".$GLOBALS["sStyleHeight"]."'></iframe>".
"</body></html>";
}
function ShowStyleCode(){
echo "<table border=0 cellspacing=1 align=center class=list>".
"<tr><th>样式（".HTMLEncode($GLOBALS["sStyleName"])."）的最佳调用代码如下（其中XXX按实际关联的表单项进行修改）：</th></tr>".
"<tr><td><textarea rows=5 cols=65 style='width:100%'><IFRAME ID=\"eWebEditor1\" SRC=\"ewebeditor.htm?id=XXX&style=".$GLOBALS["sStyleName"]."\" FRAMEBORDER=\"0\" SCROLLING=\"no\" WIDTH=\"".$GLOBALS["sStyleWidth"]."\" HEIGHT=\"".$GLOBALS["sStyleHeight"]."\"></IFRAME></textarea></td></tr>".
"</table>";
}
function ShowToolBarList(){
ShowMessage("<b class=blue>样式（".HTMLEncode($GLOBALS["sStyleName"])."）下的工具栏管理：</b>");
$nMaxOrder = 0;
for ($i=1;$i<=count($GLOBALS["aToolbar"]);$i++){
$aCurrToolbar = explode("|||", $GLOBALS["aToolbar"][$i]);
if ($aCurrToolbar[0] == $GLOBALS["sStyleID"]) {
if ((int)($aCurrToolbar[3]) > $nMaxOrder) {
$nMaxOrder = (int)($aCurrToolbar[3]);
}}}
$nMaxOrder = $nMaxOrder + 1;
$s_AddForm = "<hr width='80%' align=center size=1><table border=0 cellpadding=4 cellspacing=0 align=center>".
"<form action='?id=".$GLOBALS["sStyleID"]."&action=toolbaradd' name='addform' method=post>".
"<tr><td>工具栏名：<input type=text name=d_name size=20 class=input value='工具栏".$nMaxOrder."'> 排序号：<input type=text name=d_order size=5 value='".$nMaxOrder."' class=input> <input type=submit name=b1 value='新增工具栏'></td></tr>".
"</form></table><hr width='80%' align=center size=1>";
$s_ModiForm = "<form action='?id=".$GLOBALS["sStyleID"]."&action=toolbarmodi' name=modiform method=post>".
"<table border=0 cellpadding=0 cellspacing=1 align=center class=form>".
"<tr align=center><th>ID</th><th>工具栏名</th><th>排序号</th><th>操作</th></tr>";
for ($i=1;$i<=count($GLOBALS["aToolbar"]);$i++){
$aCurrToolbar = explode("|||", $GLOBALS["aToolbar"][$i]);
if ($aCurrToolbar[0] == $GLOBALS["sStyleID"]){
$s_Manage = "<a href='?id=".$GLOBALS["sStyleID"]."&action=buttonset&toolbarid=".$i."'>按钮设置</a>";
$s_Manage = $s_Manage."|<a href='?id=".$GLOBALS["sStyleID"]."&action=toolbardel&delid=".$i."'>删除</a>";
$s_ModiForm = $s_ModiForm."<tr align=center>".
"<td>".$i."</td>".
"<td><input type=text name='d_name".$i."' value=\"".HTMLEncode($aCurrToolbar[2])."\" size=30 class=input></td>".
"<td><input type=text name='d_order".$i."' value='".$aCurrToolbar[3]."' size=5 class=input></td>".
"<td>".$s_Manage."</td>".
"</tr>";
}}
$s_ModiForm = $s_ModiForm."<tr><td colspan=4 align=center><input type=submit name=b1 value='  修改  '></td></tr></table></form>";
echo $s_AddForm.$s_ModiForm;
}
function DoToolBarAdd(){
$s_Name = TrimGet("d_name");
$s_Order = TrimGet("d_order");
if ($s_Name == "") {
GoError("工具栏名不能为空！");
}
if (!is_numeric($s_Order)){
GoError("无效的工具栏排序号，排序号必须为数字！");
}
$nToolbarNum = count($GLOBALS["aToolbar"]) + 1;
$GLOBALS["aToolbar"][$nToolbarNum] = $GLOBALS["sStyleID"]."||||||".$s_Name."|||".$s_Order;
WriteConfig();
echo "<script language=javascript>alert(\"工具栏（".HTMLEncode($s_Name)."）增加操作成功！\");</script>";
GoUrl("?action=toolbar&id=".$GLOBALS["sStyleID"]);
}
function DoToolBarModi(){
for ($i=1;$i<=count($GLOBALS["aToolbar"]);$i++){
$aCurrToolbar = explode("|||", $GLOBALS["aToolbar"][$i]);
if ($aCurrToolbar[0] == $GLOBALS["sStyleID"]){
$s_Name = TrimGet("d_name".$i);
$s_Order = TrimGet("d_order".$i);
if (($s_Name == "") || (is_numeric($s_Order) == false)) {
$aCurrToolbar[0] = "";
$s_Name = "";
}
$GLOBALS["aToolbar"][$i] = $aCurrToolbar[0]."|||".$aCurrToolbar[1]."|||".$s_Name."|||".$s_Order;
}}
WriteConfig();
echo "<script language=javascript>alert('工具栏修改操作成功！');</script>";
GoUrl("?action=toolbar&id=".$GLOBALS["sStyleID"]);
}
function DoToolBarDel(){
$s_DelID = TrimGet("delid");
if (is_numeric($s_DelID)){
$GLOBALS["aToolbar"][$s_DelID] = "";
WriteConfig();
echo "<script language=javascript>alert('工具栏（ID：".$s_DelID."）删除操作成功！');</script>";
GoUrl("?action=toolbar&id=".$GLOBALS["sStyleID"]);
}}
function InitToolBar(){
$b = false;
$GLOBALS["sToolBarID"] = TrimGet("toolbarid");
if (is_numeric($GLOBALS["sToolBarID"])){
if (((int)($GLOBALS["sToolBarID"]) <= count($GLOBALS["aToolbar"])) && ((int)($GLOBALS["sToolBarID"]) > 0)) {
$aCurrToolbar = explode("|||", $GLOBALS["aToolbar"][$GLOBALS["sToolBarID"]]);
$GLOBALS["sToolBarName"] = $aCurrToolbar[2];
$GLOBALS["sToolBarOrder"] = $aCurrToolbar[3];
$GLOBALS["sToolBarButton"] = $aCurrToolbar[1];
$b = true;
}}
if ($b == false) {
GoError("无效的工具栏ID号，请通过页面上的链接进行操作！");
}}
function ShowButtonList(){
ShowMessage("<b class=blue>当前样式：<span class=red>".HTMLEncode($GLOBALS["sStyleName"])."</span>&nbsp;&nbsp;当前工具栏：<span class=red>".HTMLEncode($GLOBALS["sToolBarName"])."</span></b>");
echo "<script language='javascript' src='../js/buttons.js'></script>";
echo "<script language='javascript' src='../language/zh-cn.js'></script>";
echo "<table border=0 cellpadding=5 cellspacing=0 align=center>".
"<form action='?action=buttonsave&id=".$GLOBALS["sStyleID"]."&toolbarid=".$GLOBALS["sToolBarID"]."' method=post name=myform>".
"<tr align=center><td>可选按钮</td><td></td><td>已选按钮</td><td></td></tr>".
"<tr>".
"<td><DIV id=div1 style='BORDER-RIGHT: 1.5pt inset; PADDING-RIGHT: 0px; BORDER-TOP: 1.5pt inset; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; OVERFLOW: auto; BORDER-LEFT: 1.5pt inset; WIDTH: 250px; PADDING-TOP: 0px; BORDER-BOTTOM: 1.5pt inset; HEIGHT: 350px; BACKGROUND-COLOR: white'></DIV></td>".
"<td><input type=button name=b1 value=' → ' onclick='Add()'><br><br><input type=button name=b1 value=' ← ' onclick='Del()'></td>".
"<td><DIV id=div2 style='BORDER-RIGHT: 1.5pt inset; PADDING-RIGHT: 0px; BORDER-TOP: 1.5pt inset; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; OVERFLOW: auto; BORDER-LEFT: 1.5pt inset; WIDTH: 250px; PADDING-TOP: 0px; BORDER-BOTTOM: 1.5pt inset; HEIGHT: 350px; BACKGROUND-COLOR: white'></DIV></td>".
"<td><input type=button name=b3 value='↑' onclick='Up()'><br><br><br><input type=button name=b4 value='↓' onclick='Down()'></td>".
"</tr>".
"<input type=hidden name='d_button' value='".$GLOBALS["sToolBarButton"]."'>".
"<tr><td colspan=4 align=right><input type=submit name=b value=' 保存设置 '></td></tr>".
"</form>".
"</table>";
echo "<script language=javascript>".
"initButtonOptions('".$GLOBALS["sSkin"]."');".
"</script>";
ShowMessage("<b class=blue>提示：</b>你可以通过按“Ctrl”“Shift”来快速多选定，可以在指定项上“双击”快速增加或删除项。可以选定多个按钮同时上移或下移操作。");
}
function DoButtonSave(){
$s_Button = TrimGet("d_button");
$nToolBarID = (int)($GLOBALS["sToolBarID"]);
$aCurrToolbar = explode("|||", $GLOBALS["aToolbar"][$nToolBarID]);
$GLOBALS["aToolbar"][$nToolBarID] = $aCurrToolbar[0]."|||".$s_Button."|||".$aCurrToolbar[2]."|||".$aCurrToolbar[3];
WriteConfig();
ShowMessage("<b><span class=red>工具栏按钮设置保存成功！</span></b><li><a href='?action=stylepreview&id=".$GLOBALS["sStyleID"]."' target='_blank'>预览此样式</a><li><a href='?action=toolbar&id=".$GLOBALS["sStyleID"]."'>返回工具栏管理</a><li><a href='?action=buttonset&id=".$GLOBALS["sStyleID"]."&toolbarid=".$GLOBALS["sToolBarID"]."'>重新设置此工具栏下的按钮</a>");
}
?>