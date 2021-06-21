<?php header("content-Type: text/html; charset=utf-8");?>
<HTML>
<HEAD>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<TITLE>eWebEditor ： 分页显示处理示例</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='example.css'>
</HEAD>
<BODY>

<p><b>导航 ： <a href="default.php">示例首页</a> &gt; 分页显示处理示例</b></p>
<p>此例演示了eWebEditor的标准分页模式下，程序对标准分页符的处理方法。您可以查看此页程序源代码，以了解标准分页符结构及使用方法。</p>


<?php
// eWebEditor 标准分页符格式定义：
// -------------------------------------------------------------------
// <!--ewebeditor:page title="第N页小标题"-->
// 第N页正文HTML代码
// <!--/ewebeditor:page-->
// -------------------------------------------------------------------





// sContent变量：所编辑的内容，一般是从数据库中取出，以下为模拟数据
// $sContent = rs("field")
$sContent = "<!--ewebeditor:page title=\"第一页小标题\"-->\r\n".
           "<style>\r\n".
		   ".p1{font-size:14px;color:#000000;}\r\n".
		   ".p2{font-size:16px;color:#ff0000;}\r\n".
		   ".p3{font-size:18px;color:#0000ff;}\r\n".
		   "</style>\r\n".
           "<p class=p1>第一页正文</p>\r\n".
           "<!--/ewebeditor:page-->\r\n".
		   "<!--ewebeditor:page title=\"第二页小标题\"-->\r\n".
           "<p class=p2>第二页正文</p>\r\n".
           "<!--/ewebeditor:page-->\r\n".
		   "<!--ewebeditor:page title=\"第三页小标题\"-->\r\n".
           "<p class=p3>第三页正文</p>\r\n".
           "<!--/ewebeditor:page-->";
// $sContent = "<p>只有一页！</p>";



$sPage = TrimGet("page");
$arr = eWebEditorPagination($sContent, $sPage);
$sOutputContent = $arr[1];
$sOutputTitles = $arr[2];

// 显示标题列表及分页链接
if ($sOutputTitles != "") {
	echo "<hr size=1>";
	echo $sOutputTitles;
}

// 显示正文
echo "<hr size=1>";
echo $sOutputContent;






// eWebEditor 标准分页符格式定义：
// -------------------------------------------------------------------
// <!--ewebeditor:page title="第N页小标题"-->
// 第N页正文HTML代码
// <!--/ewebeditor:page-->
// -------------------------------------------------------------------
// eWebEditor标准分页符分析处理函数示列, 可依实际需要修改, 返回多值数组
// -------------------------------------------------------------------
function eWebEditorPagination($s_Content, $s_CurrPage){
	// 小标题列表，当前页标题，当前页内容
	$s_Titles = "";
	$s_CurrTitle = "";
	$s_CurrContent = $s_Content;

	// 页数：0表示没有分页
	$n_PageCount = 0;

	// 当前页
	$n_CurrPage = 1;

	// 当有分页时，存分页正文和标题的数组，下标从1开始
	$a_PageContent[] = "";
	$a_PageTitle[] = "";

	// 正则表达式对象
	// 分离出内容中的CSS样式部分，然后在各页中合并，使各分页的显示效果不变
	// <style>...</style>
	$s_Style = "";
	$s_Pattern = "/(<style[^>]*>(.+?)<\/style>)/is";
	if (preg_match_all($s_Pattern, $s_CurrContent, $ms)){
		for ($i=0; $i<count($ms[0]); $i++) {
			$s_Style = "\r\n".$s_Style.$ms[0][$i]."\r\n";
		}
		$s_CurrContent = preg_replace($s_Pattern, "", $s_CurrContent);
	}

	// 使用正则表达式对分页进行处理
	$s_Pattern = "/<!--ewebeditor:page title=\"([^\">]*)\"-->(.+?)<!--\/ewebeditor:page-->/is";
	if (preg_match_all($s_Pattern, $s_CurrContent, $ms)){
		for ($i=0; $i<count($ms[0]); $i++) {
			$n_PageCount = $n_PageCount + 1;
			$a_PageTitle[] = $ms[1][$i];
			$a_PageContent[] = $ms[2][$i];
		}
	}
	

	if ($n_PageCount == 0){
		// 没有分页
		$s_Titles = "";
		$s_CurrContent = $s_Content;
	}else{
		// 有分页
		// 从参数判断当前页的有效性
		if (!is_numeric($s_CurrPage)){
			$n_CurrPage = 1;
		}else{
			$n_CurrPage = intval($s_CurrPage);
			if ($n_CurrPage < 1 || $n_CurrPage > $n_PageCount){
				$n_CurrPage = 1;
			}
		}

		// 当有多个页时，显示分页小标题及分页链接
		$s_Titles = "";
		for ($i=1; $i<=$n_PageCount; $i++) {
			if ($i == $n_CurrPage){
				$s_Titles = $s_Titles . "<li>" . $i . ") " . $a_PageTitle[$i];
			}else{
				$s_Titles = $s_Titles . "<li><a href='?page=" . $i . "'>" . $i . ") " . $a_PageTitle[$i] . "</a>";
			}
		}

		// 当前页标题和内容
		$s_CurrTitle = $a_PageTitle[$n_CurrPage];
		$s_CurrContent = $s_Style . $a_PageContent[$n_CurrPage];
	}

	// 返回值数组，依实际需要修改
	$ret[] = "";
	$ret[] = $s_CurrContent;		//当前页内容
	$ret[] = $s_Titles;				//标题列表
	$ret[] = $s_CurrTitle;			//当前页标题

	return $ret;
}


function TrimGet($p){
	if (isset($_GET[$p])){
		return trim($_GET[$p]);
	}else{
		return "";
	}
}

?>

</BODY>
</HTML>