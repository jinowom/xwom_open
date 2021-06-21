/*
*######################################
* eWebEditor V12.1 - Advanced online web based WYSIWYG HTML editor.
* Copyright (c) 2003-2020 eWebSoft.com
*
* For further information go to http://www.ewebeditor.net/
* This copyright notice MUST stay intact for use.
*######################################
*/

var EWEBPunycode = (function(){
	var _maxInt = 2147483647;
	var _base = 36;
	var _tMin = 1;
	var _tMax = 26;
	var _skew = 38;
	var _damp = 700;
	var _initialBias = 72;
	var _initialN = 128;
	var _regexPunycode = /^xn--/;
	var _regexNonASCII = /[^\x20-\x7E]/;
	var _regexSeparators = /[\x2E\u3002\uFF0E\uFF61]/g;

	var _baseMinusTMin = _base - _tMin;
	var _floor = Math.floor;
	var _stringFromCharCode = String.fromCharCode;

	var _map = function(a, fn) {
		var l = a.length;
		var a_Ret = [];
		while (l--) {
			a_Ret[l] = fn(a[l]);
		}
		return a_Ret;
	};

	var _mapDomain = function(s, fn) {
		var a_parts = s.split('@');
		var s_Ret = '';
		if (a_parts.length > 1) {
			s_Ret = a_parts[0] + '@';
			s = a_parts[1];
		}
		var a_labels = s.split(_regexSeparators);
		var s_encoded = _map(a_labels, fn).join('.');
		return s_Ret + s_encoded;
	};

	var _ucs2decode = function(s) {
		var a_Output = [],
		    n_counter = 0,
		    l = s.length,
		    v,
		    n_extra;
		while (n_counter < l) {
			v = s.charCodeAt(n_counter++);
			if (v >= 0xD800 && v <= 0xDBFF && n_counter < l) {
				n_extra = s.charCodeAt(n_counter++);
				if ((n_extra & 0xFC00) == 0xDC00) {
					a_Output.push(((v & 0x3FF) << 10) + (n_extra & 0x3FF) + 0x10000);
				} else {
					a_Output.push(v);
					counter--;
				}
			} else {
				a_Output.push(v);
			}
		}
		return a_Output;
	};

	var _ucs2encode = function(a) {
		return _map(a, function(v) {
			var s_Output = '';
			if (v > 0xFFFF) {
				v -= 0x10000;
				s_Output += _stringFromCharCode(v >>> 10 & 0x3FF | 0xD800);
				v = 0xDC00 | v & 0x3FF;
			}
			s_Output += _stringFromCharCode(v);
			return s_Output;
		}).join('');
	};

	var _basicToDigit = function(n_codePoint) {
		if (n_codePoint - 48 < 10) {
			return n_codePoint - 22;
		}
		if (n_codePoint - 65 < 26) {
			return n_codePoint - 65;
		}
		if (n_codePoint - 97 < 26) {
			return n_codePoint - 97;
		}
		return _base;
	};

	var _digitToBasic = function(n_digit, flag) {
		return n_digit + 22 + 75 * (n_digit < 26) - ((flag != 0) << 5);
	};

	var _adapt = function(n_delta, numPoints, firstTime) {
		var k = 0;
		n_delta = firstTime ? _floor(n_delta / _damp) : n_delta >> 1;
		n_delta += _floor(n_delta / numPoints);
		for (; n_delta > _baseMinusTMin * _tMax >> 1; k += _base) {
			n_delta = _floor(n_delta / _baseMinusTMin);
		}
		return _floor(k + (_baseMinusTMin + 1) * n_delta / (n_delta + _skew));
	};

	var _decode = function(s_Input) {
		var a_Output = [],
		    n_InputLength = s_Input.length,
		    out,
		    i = 0,
		    n = _initialN,
		    n_bias = _initialBias,
		    n_basic,
		    j,
		    n_index,
		    n_oldi,
		    w,
		    k,
		    n_digit,
		    t,
		    n_baseMinusT;

		n_basic = s_Input.lastIndexOf('-');
		if (n_basic < 0) {
			n_basic = 0;
		}

		for (j = 0; j < n_basic; ++j) {
			if (s_Input.charCodeAt(j) >= 0x80) {
				return s_Input;
			}
			a_Output.push(s_Input.charCodeAt(j));
		}

		for (n_index = n_basic > 0 ? n_basic + 1 : 0; n_index < n_InputLength; ) {
			for (n_oldi = i, w = 1, k = _base; ; k += _base) {

				if (n_index >= n_InputLength) {
					return s_Input;
				}

				n_digit = _basicToDigit(s_Input.charCodeAt(n_index++));

				if (n_digit >= _base || n_digit > _floor((_maxInt - i) / w)) {
					return s_Input;
				}

				i += n_digit * w;
				t = k <= n_bias ? _tMin : (k >= n_bias + _tMax ? _tMax : k - n_bias);

				if (n_digit < t) {
					break;
				}

				n_baseMinusT = _base - t;
				if (w > _floor(_maxInt / n_baseMinusT)) {
					return s_Input;
				}

				w *= n_baseMinusT;

			}

			out = a_Output.length + 1;
			n_bias = _adapt(i - n_oldi, out, n_oldi == 0);

			if (_floor(i / out) > _maxInt - n) {
				return s_Input;
			}

			n += _floor(i / out);
			i %= out;

			a_Output.splice(i++, 0, n);
		}

		return _ucs2encode(a_Output);
	};

	var _encode = function(s_Input) {
		var n,
		    n_delta,
		    n_handledCPCount,
		    n_basicLength,
		    n_bias,
		    j,
		    m,
		    q,
		    k,
		    t,
		    n_CurrentValue,
		    a_Output = [],
		    n_InputLength,
		    n_handledCPCountPlusOne,
		    n_baseMinusT,
		    n_qMinusT;

		s_Input = _ucs2decode(s_Input);
		n_InputLength = s_Input.length;

		n = _initialN;
		n_delta = 0;
		n_bias = _initialBias;

		for (j = 0; j < n_InputLength; ++j) {
			n_CurrentValue = s_Input[j];
			if (n_CurrentValue < 0x80) {
				a_Output.push(_stringFromCharCode(n_CurrentValue));
			}
		}

		n_handledCPCount = n_basicLength = a_Output.length;

		if (n_basicLength) {
			a_Output.push('-');
		}

		while (n_handledCPCount < n_InputLength) {
			for (m = _maxInt, j = 0; j < n_InputLength; ++j) {
				n_CurrentValue = s_Input[j];
				if (n_CurrentValue >= n && n_CurrentValue < m) {
					m = n_CurrentValue;
				}
			}

			n_handledCPCountPlusOne = n_handledCPCount + 1;
			if (m - n > _floor((_maxInt - n_delta) / n_handledCPCountPlusOne)) {
				return s_Input;
			}

			n_delta += (m - n) * n_handledCPCountPlusOne;
			n = m;

			for (j = 0; j < n_InputLength; ++j) {
				n_CurrentValue = s_Input[j];

				if (n_CurrentValue < n && ++n_delta > _maxInt) {
					return s_Input;
				}

				if (n_CurrentValue == n) {
					for (q = n_delta, k = _base; ; k += _base) {
						t = k <= n_bias ? _tMin : (k >= n_bias + _tMax ? _tMax : k - n_bias);
						if (q < t) {
							break;
						}
						n_qMinusT = q - t;
						n_baseMinusT = _base - t;
						a_Output.push(
							_stringFromCharCode(_digitToBasic(t + n_qMinusT % n_baseMinusT, 0))
						);
						q = _floor(n_qMinusT / n_baseMinusT);
					}

					a_Output.push(_stringFromCharCode(_digitToBasic(q, 0)));
					n_bias = _adapt(n_delta, n_handledCPCountPlusOne, n_handledCPCount == n_basicLength);
					n_delta = 0;
					++n_handledCPCount;
				}
			}

			++n_delta;
			++n;
		}
		return a_Output.join('');
	};

	var _DecodeDomain = function(s_Input) {
		return _mapDomain(s_Input, function(s) {
			return _regexPunycode.test(s) ? _decode(s.slice(4).toLowerCase()) : s;
		});
	};

	var _EncodeDomain = function(s_Input) {
		return _mapDomain(s_Input, function(s) {
			return _regexNonASCII.test(s) ? 'xn--' + _encode(s) : s;
		});
	};

	var _ParseUrl = function(s_Url, b_IsEncode){
		var n = s_Url.indexOf("://");
		var s_Left = "";
		if (n>0){
			s_Left = s_Url.substr(0,n+3);
			s_Url = s_Url.substr(n+3);
		}else{
			return s_Url;
		}

		var s_Right = "";
		n = s_Url.indexOf("/");
		if (n>0){
			s_Right = s_Url.substr(n);
			s_Url = s_Url.substr(0,n);
		}
		n = s_Url.indexOf(":");
		if (n>0){
			s_Right = s_Url.substr(n) + s_Right;
			s_Url = s_Url.substr(0,n);
		}

		return s_Left + (b_IsEncode ? _EncodeDomain(s_Url) : _DecodeDomain(s_Url)) + s_Right;
	};

	return {
		DecodeDomain : function(s_Input) {
			return _DecodeDomain(s_Input);
		},

		EncodeDomain : function(s_Input) {
			return _EncodeDomain(s_Input);
		},
		
		DecodeUrl : function(s_Input){
			return _ParseUrl(s_Input, false);
		},

		EncodeUrl : function(s_Input){
			return _ParseUrl(s_Input, true);
		}

	};

})();

function BaseTrim(str){
	lIdx=0;
	rIdx=str.length;
	if (BaseTrim.arguments.length==2){
		act=BaseTrim.arguments[1].toLowerCase();
	}else{
		act="all";
	}

	for(var i=0;i<str.length;i++){
		thelStr=str.substring(lIdx,lIdx+1);
		therStr=str.substring(rIdx,rIdx-1);
		if ((act=="all" || act=="left") && thelStr==" "){
			lIdx++;
		}
		if ((act=="all" || act=="right") && therStr==" "){
			rIdx--;
		}
	}
	str=str.slice(lIdx,rIdx);
	return str;
}

function BaseAlert(theText,notice){
	alert(notice);
	theText.focus();
	theText.select();
	return false;
}

function isNotFloat(theFloat){
	len=theFloat.length;
	dotNum=0;
	if (len==0){
		return true;
	}
	for(var i=0;i<len;i++){
	    oneNum=theFloat.substring(i,i+1);
		if (oneNum=="."){
			dotNum++;
		}
        if ( ((oneNum<"0" || oneNum>"9") && oneNum!=".") || dotNum>1){
          return true;
		}
    }
	if (len>1 && theFloat.substring(0,1)=="0"){
		if (theFloat.substring(1,2)!="."){
			return true;
		}
	}
	return false;
}

function isNotNum(theNum){
	if (BaseTrim(theNum)==""){
		return true;
	}
	for(var i=0;i<theNum.length;i++){
	    oneNum=theNum.substring(i,i+1);
        if (oneNum<"0" || oneNum>"9"){
          return true;
		}
    }
	return false;
}

function isNotInt(theInt){
	theInt=BaseTrim(theInt);
	if ((theInt.length>1 && theInt.substring(0,1)=="0") || isNotNum(theInt)){
		return true;
	}
	return false;
}


function HighLightOver(ev){
	HighLightList(ev, "#E0E6F7");
}
function HighLightOut(ev){
	HighLightList(ev, "");
}

function HighLightList(ev, color){
	if (!ev){
		ev = window.event;
	}
	var el=ev.srcElement || ev.target;
	var b=false;
	var tabElement=null;
	while (!b){
		el=GetParentElement(el, "TR");
		if (el){
			tabElement=GetParentElement(el, "TABLE");
			if (tabElement!=null && tabElement.className.toUpperCase()=="LIST"){
				break;
			}
			el=tabElement;
		}else{
			return;
		}
	}
	
	for (var i=0;i<el.children.length;i++){
		if (el.children[i].tagName=="TD"){
			el.children[i].style.backgroundColor=color;
		}
	}
}

function GetParentElement(obj, tag){
	while(obj!=null && obj.tagName!=tag){
		//obj=obj.parentElement;
		obj=obj.parentNode;
	}
	return obj;
}

function doCheckWH(flag){
	var oForm = document.myform;
	if (flag==1){
		tdPreview.innerHTML="<span style='font-size:"+oForm.d_syfontsize.value+";font-family:"+oForm.d_syfontname.value+"'>"+oForm.d_sytext.value+"</span>";
		oForm.d_sywztextwidth.value=tdPreview.offsetWidth;
		oForm.d_sywztextheight.value=tdPreview.offsetHeight;
	}else{
		var url=oForm.d_sypicpath.value;
		if (url==""){
			oForm.d_sytpimagewidth.value="0";
			oForm.d_sytpimageheight.value="0";
			tdPreview.innerHTML="";
		}else{
			if ((url.substring(0,1)!=".")&&(url.substring(0,1)!="/")){
				url="../"+getAppExt()+"/"+url;
			}
			tdPreview.innerHTML="<img border=0 src='"+url+"' onload='setCheckWH()' onerror='ErrorCheckWH()'>";
		}
	}
}

function getAppExt(){
	var p = location.pathname;
	var n = p.lastIndexOf(".");
	return p.substr(n+1).toLowerCase();
}

function setCheckWH(){
	document.myform.d_sytpimagewidth.value=tdPreview.offsetWidth;
	document.myform.d_sytpimageheight.value=tdPreview.offsetHeight;
}

function ErrorCheckWH(){
	BaseAlert(document.myform.d_sypicpath,"无效的图片水印图片路径！");
}

function doCheckAll(obj){
	var form = obj.form;
	for (var i=0;i<form.elements.length;i++){
		var e = form.elements[i];
		e.checked = obj.checked;
	}
}

document.onmouseover=HighLightOver;
document.onmouseout=HighLightOut;



function checkStyleSetForm(f){
	var o, v, re;

	o = f.d_name;
	v = trimObj(o);
	if (!v){
		return BaseAlert(o, "样式名：不能为空！");
	}else{
		re = new RegExp("[^a-zA-Z0-9_\-]+","gi");
		if (re.test(v)){
			return BaseAlert(o, "样式名：只能由英文字母、下划线、中划线组成！");
		}
	}

	o = f.d_fixwidth;
	v = trimObj(o);
	if (v){
		if (isNaN(parseInt(v))||v.substr(0,1)=="-"){
			return BaseAlert(o, "限宽模式宽度：值无效，如果不启用，请留空！");
		}
	}

	o = f.d_skin;
	v = trimObj(o);
	if (!v){
		return BaseAlert(o, "界面皮肤目录名：不能为空！");
	}else{
		re = new RegExp("[^a-zA-Z0-9_\-]+","gi");
		if (re.test(v)){
			return BaseAlert(o, "界面皮肤目录名：只能由英文字母、下划线、中划线组成！");
		}
	}

	o = f.d_width;
	v = trimObj(o);
	if (isNaN(parseInt(v))){
		return BaseAlert(o, "最佳引用宽度：不能为空，且必须是数字！");
	}

	o = f.d_height;
	v = trimObj(o);
	if (isNaN(parseInt(v))){
		return BaseAlert(o, "最佳引用高度：不能为空，且必须是数字！");
	}

	o = f.d_encryptkey;
	v = trimObj(o);
	if (hasSpecialChar(v)){
		return BaseAlert(o, "安全接口加密串：不能包含特殊字符，只能由字母和数字组成，建议点“随机”自动生成！");
	}
	if (f.d_advapiflag.selectedIndex==2){
		if (!v){
			return BaseAlert(o, "当启用高级安全接口时，安全接口加密串：不能为空！");
		}
	}

	o = f.d_memo;
	v = trimObj(o);
	if (hasSpecialCharBasic(v)){
		return BaseAlert(o, "备注说明：不能含有基本特殊字符“'\"|”！");
	}

	o = f.d_resmovepath;
	v = trimObj(o);
	if (v){
		v = v.replace(/\\/g, "/");
		if (v.substr(v.length-1)!="/"){
			v += "/";
		}
	}
	o.value = v;


	//上传相关
	o = f.d_autodir;
	v = trimObj(o);
	if (v){
		re = /[\' 　\&\<\>\?\%\,\;\(\)\`\~\!\@\#\$\^\*\[\]\|\"\t\n\.]+/gi;
		if (re.test(v)){
			return BaseAlert(o, "年月日自动目录：不能含用特殊字符！");
		}
		v = v.replace(/\\/g, "/");
		if (v.substr(v.length-1)!="/"){
			v += "/";
		}
		o.value = v;
		if (v.substr(0,1)=="/"){
			return BaseAlert(o, "年月日自动目录：不能以“/”开头！");
		}
	}

	o = f.d_uploaddir;
	v = trimObj(o);
	if (!v || hasSpecialChar(v)){
		return BaseAlert(o, "上传路径：不能为空，且不能包含特殊字符！");
	}

	switch(f.d_baseurl.value){
	case "0":
		v = v.replace(/\\/g, "/");
		if (v.substr(v.length-1)!="/"){
			v += "/";
		}
		o.value = v;

		o = f.d_basehref;
		v = trimObj(o);
		v = v.replace(/\\/g, "/");
		if (!v || v.substr(0,1)!="/" || hasSpecialChar(v)){
			return BaseAlert(o, "显示路径：当使用相对路径模式时，不能为空，且不能包含特殊字符，且必须以“/”开头！");
		}
		if (v.substr(v.length-1)!="/"){
			v += "/";
		}
		o.value = v;

		o = f.d_contentpath;
		v = trimObj(o);
		if (v){
			v = v.replace(/\\/g, "/");			
			if (v.substr(0,1)=="/"){
				return BaseAlert(o, "内容路径：当使用相对路径模式时，不能以“/”开头！");
			}
			if (v.substr(v.length-1)!="/"){
				v += "/";
			}
		}
		o.value = v;

		break;
	case "1":
	case "2":
		f.d_basehref.value = "";
		f.d_contentpath.value = "";
		break;
	case "3":
		var s = v.substr(v.length-1);
		if ((s!="/") && (s!="\\")){
			if (v.indexOf("/")>=0){
				v+="/";
			}else{
				v+="\\";
			}
			o.value=v;
		}

		f.d_basehref.value = "";

		o = f.d_contentpath;
		v = trimObj(o);
		if (!v){
			return BaseAlert(o, "内容路径：当使用站外绝对全路径模式时，不能为空，且不能包含特殊字符！");
		}
		v = v.replace(/\\/g, "/");
		if (v.substr(v.length-1)!="/"){
			v += "/";
		}
		o.value = v;

		break;
	}

	o = f.d_fileserverpath;
	v = trimObj(o);
	if (v){
		v = v.replace(/\\/g, "/");
		if (v.substr(v.length-1)!="/"){
			v += "/";
		}

		if (f.d_baseurl.value!="2" && f.d_baseurl.value!="3"){
			return BaseAlert(o, "当启用文件服务接口时，[路径模式] 只能用 [绝对全路径] 或 [站外绝对全路径] ！");
		}
	}
	o.value = v;


	// 上传文件类型及大小
	o = f.d_spacesize;
	v = trimObj(o);
	if (v){
		if (isNotInt(v)){
			return BaseAlert(o, "总上传空间限制：必须是数字！");
		}
		if (IsGreaterThanLong(o, v)){
			return false;
		}
	}

	o = f.d_imageext;
	v = trimObj(o);
	if (!isValidExtSet(v)){
		return BaseAlert(o, "图片类型：不能为空，且格式为“ext1|ext2”！");
	}

	o = f.d_imagesize;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "图片限制：不能为空，且必须是数字！");
	}
	if (IsGreaterThanLong(o, v)){
		return false;
	}

	o = f.d_flashext;
	v = trimObj(o);
	if (!isValidExtSet(v)){
		return BaseAlert(o, "Flash类型：不能为空，且格式为“ext1|ext2”！");
	}

	o = f.d_flashsize;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "Flash限制：不能为空，且必须是数字！");
	}
	if (IsGreaterThanLong(o, v)){
		return false;
	}

	o = f.d_mediaext;
	v = trimObj(o);
	if (!isValidExtSet(v)){
		return BaseAlert(o, "媒体类型：不能为空，且格式为“ext1|ext2”！");
	}

	o = f.d_mediasize;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "媒体限制：不能为空，且必须是数字！");
	}
	if (IsGreaterThanLong(o, v)){
		return false;
	}

	o = f.d_fileext;
	v = trimObj(o);
	if (!isValidExtSet(v)){
		return BaseAlert(o, "附件类型：不能为空，且格式为“ext1|ext2”！");
	}

	o = f.d_filesize;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "附件限制：不能为空，且必须是数字！");
	}
	if (IsGreaterThanLong(o, v)){
		return false;
	}

	o = f.d_remoteext;
	v = trimObj(o);
	if (!isValidExtSet(v)){
		return BaseAlert(o, "远程文件类型：不能为空，且格式为“ext1|ext2”！");
	}

	o = f.d_remotesize;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "远程文件限制：不能为空，且必须是数字！");
	}
	if (IsGreaterThanLong(o, v)){
		return false;
	}

	o = f.d_localext;
	v = trimObj(o);
	if (!isValidExtSet(v)){
		return BaseAlert(o, "本地文件类型：不能为空，且格式为“ext1|ext2”！");
	}

	o = f.d_localsize;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "本地文件限制：不能为空，且必须是数字！");
	}
	if (IsGreaterThanLong(o, v)){
		return false;
	}

	// 分页相关
	o = f.d_paginationkey;
	v = trimObj(o);
	if (hasSpecialCharBasic(v)){
		return BaseAlert(o, "分页符关键字：不能含有基本特殊字符“'\"|”！");
	}

	o = f.d_paginationmode;
	if (o.selectedIndex==2){
		o = f.d_paginationkey;
		v = trimObj(o);
		if (v==""){
			return BaseAlert(o, "当使用自定义分页符模式时，分页符关键字：不能为空！");
		}
	}

	o = f.d_paginationautonum;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "自动分页字数：不能为空，且必须是数字！");
	}



	// 缩略图及水印相关
	o = f.d_sltsyext;
	v = trimObj(o);
	if (!isValidExtSet(v)){
		return BaseAlert(o, "处理图形扩展名：不能为空，且格式为“ext1|ext2”！");
	}

	o = f.d_sltminsize;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "缩略图使用最小长度条件：不能为空，且必须是数字！");
	}

	o = f.d_sltoksize;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "缩略图生成长度：不能为空，且必须是数字！");
	}

	o = f.d_sywzminwidth;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "文字水印启用的最小宽度条件：不能为空，且必须是数字！");
	}

	o = f.d_sywzminheight;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "文字水印启用的最小高度条件：不能为空，且必须是数字！");
	}

	o = f.d_sytext;
	v = trimObj(o);
	if ((!v) || (v.indexOf("|")>0)){
		return BaseAlert(o, "水印文字内容：不能为空，且不能含有特殊字符“|”！");
	}

	o = f.d_syfontcolor;
	v = trimObj(o);
	if (!isValidColor(v)){
		return BaseAlert(o, "文字水印字体颜色：必须是16进制颜色代码，6位长度，如黑色：“000000”！");
	}

	o = f.d_syshadowcolor;
	v = trimObj(o);
	if (!isValidColor(v)){
		return BaseAlert(o, "文字水印阴影颜色：必须是16进制颜色代码，6位长度，如白色：“FFFFFF”！");
	}

	o = f.d_syshadowoffset;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "文字水印阴影大小：不能为空，且必须是数字！");
	}

	o = f.d_syfontsize;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "文字水印字体大小：不能为空，且必须是数字！");
	}

	o = f.d_syfontname;
	v = trimObj(o);
	if ((!v) || (v.indexOf("|")>0)){
		return BaseAlert(o, "文字水印字体名称：不能为空，且不能含有特殊字符“|”！");
	}

	o = f.d_sywzpaddingh;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "文字水印左右边距：不能为空，且必须是数字！");
	}

	o = f.d_sywzpaddingv;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "文字水印上下边距：不能为空，且必须是数字！");
	}

	o = f.d_sywztextwidth;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "文字水印文字宽占位：不能为空，且必须是数字！");
	}

	o = f.d_sywztextheight;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "文字水印文字高占位：不能为空，且必须是数字！");
	}

	o = f.d_sytpminwidth;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "图片水印启用的最小宽度条件：不能为空，且必须是数字！");
	}

	o = f.d_sytpminheight;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "图片水印启用的最小高度条件：不能为空，且必须是数字！");
	}

	o = f.d_sytppaddingh;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "图片水印左右边距：不能为空，且必须是数字！");
	}

	o = f.d_sytppaddingv;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "图片水印上下边距：不能为空，且必须是数字！");
	}




	o = f.d_sypicpath;
	v = trimObj(o);
	if (v){
		if (hasSpecialChar(v)){
			return BaseAlert(o, "图片水印图片路径：不能包含特殊字符！");
		}
	}


	o = f.d_sytpopacity;
	v = trimObj(o);
	if (isNotFloat(v) || parseFloat(v)>1 || parseFloat(v)<0){
		return BaseAlert(o, "图片水印透明度：不能为空，且必须是0至1之间的数字！");
	}

	o = f.d_sytpimagewidth;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "图片水印文字宽占位：不能为空，且必须是数字！");
	}

	o = f.d_sytpimageheight;
	v = trimObj(o);
	if (isNotInt(v)){
		return BaseAlert(o, "图片水印文字高占位：不能为空，且必须是数字！");
	}

	return true;
}

function trimObj(obj){
	obj.value = BaseTrim(obj.value);
	return obj.value;
}

function hasSpecialChar(str){
	var re = /[\' 　\&\<\>\?\%\,\;\(\)\`\~\!\@\#\$\^\*\{\}\[\]\|\"\t\n]+/gi;
	return re.test(str);
}

function hasSpecialCharBasic(str){
	var re = /[\'\|\"\t\n]+/gi;
	return re.test(str);
}


function isValidExtSet(str){
	if (str==""){
		return false;
	}
	if (str.substr(0,1)=="|"){
		return false;
	}
	if (str.substr(str.length-1)=="|"){
		return false;
	}
	if (str.indexOf("||")>0){
		return false;
	}
	return true;
}

function isValidColor(str){
	if (str.length!=6){
		return false;
	}
	var re = new RegExp("[A-Fa-f0-9]{6}", "gi");
	return re.test(str);
}


function Add() {
	var sel = "";
	var els = div1.getElementsByTagName("DIV");
	for (var i=0; i<els.length; i++){
		if (els[i].className=="Node2"){
			sel += "|" + els[i].getAttribute("code",2);
		}
	}
	if (sel==""){
		alert("请选择一个待选按钮！");
		return;
	}

	var v = document.myform.d_button.value;
	if (v){
		v = v + sel;
	}else{
		v = sel.substr(1);
	}
	document.myform.d_button.value = v;
	reloadSelectedButtons();
	div2.scrollTop = div2.scrollHeight-div2.clientHeight;
}

function Del() {
	var sel = "";
	var els = div2.getElementsByTagName("DIV");
	for (var i=0; i<els.length; i++){
		if (els[i].className=="Node2"){
			sel += "|" + els[i].getAttribute("id",2).substr(5);
		}
	}
	if (sel==""){
		alert("请选择一个已选按钮！");
		return;
	}

	sel = sel.substr(1);
	var a1 = sel.split("|");
	var a2 = a1.reverse();
	var s_btn = document.myform.d_button.value;
	var b = s_btn.split("|");
	for (var i=0; i<a2.length; i++){
		b.splice(parseInt(a2[i]), 1);
	}

	document.myform.d_button.value = b.join("|");
	var t = div2.scrollTop;
	reloadSelectedButtons();
	div2.scrollTop = t;

}

function Up() {
	var sel = "";
	var els = div2.getElementsByTagName("DIV");
	for (var i=0; i<els.length; i++){
		if (els[i].className=="Node2"){
			sel += "|" + els[i].getAttribute("id",2).substr(5);
		}
	}
	if (sel==""){
		alert("请至少选择一个要移动的已选按钮！");
		return;
	}

	sel = sel.substr(1);
	var a = sel.split("|");
	if (a[0]=="0"){
		alert("选定按钮已在最顶，不能再往上移！");
		return;
	}

	var s_btn = document.myform.d_button.value;
	var b = s_btn.split("|");
	var j,s;
	for (var i=0; i<a.length; i++){
		j = parseInt(a[i]);
		s = b[j];
		b[j] = b[j-1];
		b[j-1] = s;
	}
	
	document.myform.d_button.value = b.join("|");
	var t = div2.scrollTop;
	reloadSelectedButtons();
	div2.scrollTop = t;

	for (var i=0; i<a.length; i++){
		j = (parseInt(a[i])-1);
		var e = document.getElementById("div2_" + j);
		e.className = "Node2";
	}
}

function Down() {
	var sel = "";
	var els = div2.getElementsByTagName("DIV");
	for (var i=0; i<els.length; i++){
		if (els[i].className=="Node2"){
			sel += "|" + els[i].getAttribute("id",2).substr(5);
		}
	}
	if (sel==""){
		alert("请至少选择一个要移动的已选按钮！");
		return;
	}

	sel = sel.substr(1);
	var a1 = sel.split("|");
	var a = a1.reverse();
	var s_btn = document.myform.d_button.value;
	var b = s_btn.split("|");

	if (parseInt(a[0])==(b.length-1)){
		alert("选定按钮已在最底，不能再往下移！");
		return;
	}

	var j,s;
	for (var i=0; i<a.length; i++){
		j = parseInt(a[i]);
		s = b[j];
		b[j] = b[j+1];
		b[j+1] = s;
	}
	
	document.myform.d_button.value = b.join("|");
	var t = div2.scrollTop;
	reloadSelectedButtons();
	div2.scrollTop = t;

	for (var i=0; i<a.length; i++){
		j = (parseInt(a[i])+1);
		var e = document.getElementById("div2_" + j);
		e.className = "Node2";
	}

}




var divLastIndex = {"div1":-1, "div2":-1};
function doClickNode(ev, el){
	var s_ID = el.getAttribute("id",2);
	var s_PID = s_ID.substr(0,4);
	var n_SelIndex = parseInt(s_ID.substr(5));
	var el_P = document.getElementById(s_PID);

	if (ev.shiftKey){
		if (divLastIndex[s_PID]==-1){
			el.className = "Node2";
		}else{
			var n_Max, n_Min;
			if (divLastIndex[s_PID]>n_SelIndex){
				n_Max = divLastIndex[s_PID];
				n_Min = n_SelIndex;
			}else{
				n_Max = n_SelIndex;
				n_Min = divLastIndex[s_PID];
			}
			for (var i=n_Min; i<=n_Max; i++){
				var e = document.getElementById(s_PID+"_"+i);
				e.className = "Node2";
			}
		}
	}else if(ev.ctrlKey){
		if (el.className=="Node2"){
			el.className = "Node1";
		}else{
			el.className = "Node2";
		}
	}else{
		var els = el_P.getElementsByTagName("DIV");
		for (var i=0; i<els.length; i++){
			if (els[i].className=="Node2"){
				els[i].className = "Node1";
			}
		}
		el.className = "Node2";
	}

	divLastIndex[s_PID] = n_SelIndex;
}

function doDblClickNode(el){
	var s_ID = el.getAttribute("id",2);
	var s_PID = s_ID.substr(0,4);
	if (s_PID=="div1"){
		Add();
	}else{
		Del();
	}
}


var sSkin = "";
function initButtonOptions(s_Skin){
	sSkin = s_Skin;
	var html1 = "";
	var s_Key = "";
	var i=0;
	for (s_Key in Buttons){
		html1 += getBtnImgHTML(s_Key, "div1_"+i);
		i++;
	}
	div1.innerHTML = html1;

	reloadSelectedButtons();
}

function reloadSelectedButtons(){
	var html2 = "";
	var s_SelBtn = document.myform.d_button.value;
	a_Btns = s_SelBtn.split("|");
	for (var i=0; i<a_Btns.length; i++){
		if (a_Btns[i]!=""){
			html2 += getBtnImgHTML(a_Btns[i], "div2_"+i);
		}
	}
	div2.innerHTML = html2;
}

function getBtnImgHTML(s_Code, s_ID){
	var a_Btn = Buttons[s_Code];
	var html = "<div id='"+s_ID+"' class='Node1' code='"+s_Code+"' onclick='doClickNode(event, this)' ondblclick='doDblClickNode(this)'><table border=0 cellpadding=0 cellspacing=1 width='100%' height=20><tr>";
	if (a_Btn[3]==0){
		html += "<td width=20 align=center unselectable=on>";
		if (typeof(a_Btn[0])=="number"){
			var s_Img = "../skin/" + sSkin + "/buttons.gif";
			var n_Top = 16-a_Btn[0]*16;
			html += "<div class='TB_Btn_Image'><img src='"+s_Img+"' style='top:"+n_Top+"px'></div>";
		}else{
			var s_Img = "../skin/" + sSkin + "/" + a_Btn[0];
			html += "<img class='TB_Btn_Image' src='"+s_Img+"'>";
		}
		html += "</td><td width='*' unselectable=on>" + lang[s_Code] + "</td>";
	}else if (a_Btn[3]==1){
		html += "<td width=20 align=center unselectable=on>-</td><td width='*' unselectable=on>下拉框："+lang[s_Code]+"</td>";
	}else{
		var s_Desc = "";
		switch(s_Code){
		case "TBSep":
			s_Desc = "分隔线";
			break;
		case "TBHandle":
			s_Desc = "工具栏头";
			break;
		case "Space":
			s_Desc = "空格";
			break;
		}
		html += "<td width=20 align=center unselectable=on>-</td><td width='*' unselectable=on>"+s_Desc+"</td>";
	}
	html += "</tr></table></div>";
	return html;
}


function checkModipwdForm() {
	var obj;
	obj=document.myform.newusr;
	obj.value=BaseTrim(obj.value);
	if (obj.value=="") {
		BaseAlert(obj, "新用户名不能为空！");
		return false;
	}
	obj=document.myform.newpwd1;
	obj.value=BaseTrim(obj.value);
	if (obj.value=="") {
		BaseAlert(obj, "新密码不能为空！");
		return false;
	}
	if (document.myform.newpwd1.value!=document.myform.newpwd2.value){
		BaseAlert(document.myform.newpwd1, "新密码和确认密码不相同！");
		return false;
	}
	return true;
}

function submitLicense(){
	var f = document.formLicense;
	f.d_url.value = EWEBPunycode.EncodeUrl(location.href);
	f.submit();
}

function CreateRndEncryptKey(){
	document.myform.d_encryptkey.value=GetRndEncryptKey(32);
}

function GetRndEncryptKey(n_Len){
	var s = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	var l = s.length;
	var ret = "";
	for (var i=0; i<n_Len; i++){
		var rnd=Math.round(Math.random()*(l-1));
		ret += s.substr(rnd, 1);
	}
	return ret;
}

function IsGreaterThanLong(o, v){
	var n_Long = 2000000000;
	if (parseInt(v)>n_Long){
		BaseAlert(o, "数值：不能大于"+n_Long);
		return true;
	}else{
		return false;
	}
}

var lang = new Object();
