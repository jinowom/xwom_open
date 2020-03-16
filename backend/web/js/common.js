/**
 * Created by WANGWEIHUA on 2019/8/4.
 */
// f => form
var F = function(form){
    this._f = form;
    this.verify = function (verify) {
        // console.log(verify);
        if(typeof verify.max != 'function'){ // verify => input[lay-max]
            verify.max = function(value,item) {
                console.log(value);
                var layMax = $(item).attr('lay-max');
                if (value.length> layMax) {
                    return '最多不能超过'+layMax+"个字符";
                }
            };
        }
        if(typeof verify.min != 'function'){
            verify.min = function(value,item) {
                var layMin = $(item).attr('lay-min');
                if (value.length < layMin) {
                    return '最少不能少于'+layMin+"个字符";
                }
            };
        }
        this._f.verify(verify);
    };

    this.submit = function(submit,bFunc,sFunc,eFunc){
        // console.log(submit);
        //监听提交
        try {
            // console.log(this);
            this._f.on('submit('+ submit +')', function(data){
                // console.log(data);
                var action = $(data.form).attr('action');
                var CajaxOption = {
                    type : "POST",
                    url : action,
                    data : data.field,
                };
                Cajax(CajaxOption,bFunc,sFunc,eFunc);
                return false;
            });
        }catch(e){
            console.log(e);
            return false;
        }

    }
    return this;
};

var icon = {ICON_WARING : 0,ICON_OK : 1, ICON_ERROR: 2,ICON_CONFIRM: 3,ICON_LOCK: 4,ICON_FAIL: 5,ICON_SUC: 6};
var load ;
var Cajax = function (option,bFunc,sFunc,eFunc) {
    var func = function (jsonData) {
        layer.close(load);
        if(jsonData.readyState != undefined){
            layer.msg('服务器错误', {icon: icon.ICON_WARING});
        }
    };
    var funcLoad = function () {
        load = layer.load(0, {shade: false});
    };
    var bFunc = (bFunc == undefined || bFunc == null || bFunc == '') ? funcLoad : bFunc;
    var sFunc = (sFunc == undefined || sFunc == null || sFunc == '') ? function(jsonData){
        layer.close(load);
        var ic = (jsonData.status == true) ? icon.ICON_OK : icon.ICON_WARING;
        layer.msg(jsonData.msg,{icon:ic});
    } :  sFunc;
    var eFunc = (eFunc == undefined || eFunc == null || eFunc == '') ? func : eFunc;
    var _opt = [];
    for(var i in option){
        _opt[i] = option[i];
    }
    _opt['beforeSend'] = bFunc;
    _opt['success'] = sFunc;
    _opt['error'] = eFunc;
    // _opt = JSON.stringify(_opt);
    $.ajax(_opt);
}

//tab Jump pranet
function pAddTab(title,url,is_refresh){
    xadmin.add_tab(title,url,is_refresh);
}

function getColumn(a,c){
    var r = new Array;
    $.each(a,function (k,v) {
        r.push(v[c]);
    })
    return r;
}

