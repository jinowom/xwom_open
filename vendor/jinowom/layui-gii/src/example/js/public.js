//定义后台常量
window.API_CODE = {
    SUCCESS: 0  //成功码的标识
}
//默认弹框的宽高
window.DEFAULT_DIALOG_LARGE_AREA = ['90%', '95%']
window.DEFAULT_DIALOG_MID_AREA = ['80%', '80%']

/**
 * 格式化时间
 * @param datetime
 * @param fmt yyyy-MM-dd hh:mm
 * @returns {*}
 * @constructor
 */
function Format(datetime,fmt) {
    if(!typeof(datetime) == 'number'){
        return datetime
    }
    datetime = datetime.toString();
    if (datetime.length==10) {
        datetime=parseInt(datetime)*1000;
    } else if(datetime.length==13) {
        datetime = parseInt(datetime);
    }
    datetime=new Date(datetime);
    var o = {
        "M+" : datetime.getMonth()+1,                 //月份
        "d+" : datetime.getDate(),                    //日
        "h+" : datetime.getHours(),                   //小时
        "m+" : datetime.getMinutes(),                 //分
        "s+" : datetime.getSeconds(),                 //秒
        "q+" : Math.floor((datetime.getMonth()+3)/3), //季度
        "S"  : datetime.getMilliseconds()             //毫秒
    };
    if(/(y+)/.test(fmt))
        fmt=fmt.replace(RegExp.$1, (datetime.getFullYear()+"").substr(4 - RegExp.$1.length));
    for(var k in o)
        if(new RegExp("("+ k +")").test(fmt))
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
    return fmt;
}

/**
 * 将原始数据解析成 table 组件所规定的数据
 * @param res
 */
function parseTableData(res) {
    let retData = {
        "code": 2000, //接口状态【接口错误】
        "msg": '服务器繁忙', //解析提示文本
        "count": 0, //解析数据长度
        "data": [], //解析数据列表
    };
    //请求返回的格式错误
    if(res.code === undefined){
        return retData;
    }
    retData.code = res.code;
    retData.msg = res.msg;
    retData.count = res.data.total;
    retData.data = res.data.lists;
    return retData;
}

/**
 * 验证手机号
 * @param v
 */
function checkPhone(v) {
    if (!(/^1(3|4|5|7|8)\d{9}$/.test(v))) {
        return false
    } else {
        return true
    }
}
