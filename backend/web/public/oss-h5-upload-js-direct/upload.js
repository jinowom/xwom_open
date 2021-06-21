function uploadDo(accessid,accesskey,host,g_dirname,timestamp){
    g_object_name_type = ''
    g_object_name = ''
    data = Date.parse(new Date());

    var policyText = {
        "expiration": timestamp,//设置该Policy的失效时间，超过这个失效时间之后，就没有办法通过这个policy上传文件了
        "conditions": [
        ["content-length-range", 0, 1024 * 1024 * 1024 * 5] // 设置上传文件的大小限制
        ]
    };
    
    var policyBase64 = Base64.encode(JSON.stringify(policyText))
    message = policyBase64
    var bytes = Crypto.HMAC(Crypto.SHA1, message, accesskey, { asBytes: true }) ;
    var signature = Crypto.util.bytesToBase64(bytes);
    
    function check_object_radio() {
        var tt = document.getElementsByName('myradio');
        for (var i = 0; i < tt.length ; i++ )
        {
            if(tt[i].checked)
            {
                g_object_name_type = tt[i].value;
                break;
            }
        }
    }
    
    function get_dirname()
    {
        dir = document.getElementById("dirname").value;
        if (dir != '' && dir.indexOf('/') != dir.length - 1)
        {
            dir = dir + '/'
        }
        //alert(dir)
        g_dirname = dir
    }
    
    function random_string(len) {
    　　len = len || 32;
    　　var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';   
    　　var maxPos = chars.length;
    　　var pwd = '';
    　　for (i = 0; i < len; i++) {
        　　pwd += chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return pwd;
    }
    
    function get_suffix(filename) {
        pos = filename.lastIndexOf('.')
        suffix = ''
        if (pos != -1) {
            suffix = filename.substring(pos)
        }
        return suffix;
    }
    
    function calculate_object_name(filename)
    {
        if (g_object_name_type == 'local_name')
        {
            g_object_name += "${filename}"
        }
        else if (g_object_name_type == 'random_name')
        {
            suffix = get_suffix(filename)
            g_object_name = g_dirname + random_string(10) + data + suffix
        }
        return ''
    }
    
    function get_uploaded_object_name(filename)
    {
        if (g_object_name_type == 'local_name')
        {
            tmp_name = g_object_name
            tmp_name = tmp_name.replace("${filename}", filename);
            return tmp_name
        }
        else if(g_object_name_type == 'random_name')
        {
            return g_object_name
        }
    }
    
    function set_upload_param(up, filename, ret)
    {
        g_object_name = g_dirname;
        if (filename != '') {
            suffix = get_suffix(filename)
            calculate_object_name(filename)
        }
        new_multipart_params = {
            'key' : g_object_name,
            'policy': policyBase64,
            'OSSAccessKeyId': accessid, 
            'success_action_status' : '200', //让服务端返回200,不然，默认会返回204
            'signature': signature,
        };
    
        up.setOption({
            'url': host,
            'multipart_params': new_multipart_params
        });
    
        up.start();
    }
    
    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'selectfiles', 
        //multi_selection: false,
        container: document.getElementById('container'),
        flash_swf_url : 'lib/plupload-2.1.2/js/Moxie.swf',
        silverlight_xap_url : 'lib/plupload-2.1.2/js/Moxie.xap',
        url : 'http://oss.aliyuncs.com',
    
        init: {
            PostInit: function() {
                // document.getElementById('ossfile').innerHTML = '';
                document.getElementById('postfiles').onclick = function() {
                set_upload_param(uploader, '', false);
                return false;
                };
            },
    
            FilesAdded: function(up, files) {
                var str = ""
                plupload.each(files, function(file) {
                    str = '<tr id="upload-'+ file.id +'">'
                            +'<td><input  type="text" lay-verify="required" class="layui-input" name="moveName[]" value="'+file.name+'" ></td>'
                            +'<td>'+ (file.size/1024).toFixed(1) +'kb <input type="hidden" name="moveSize[]" value="'+(file.size/1024).toFixed(1)+'" lay-verify="required" ></td>'
                                +'<td><div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ')<b></b>'
                                +'<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>'
                                +'</div>'
                                +'<td></td>'
                                +'<td><textarea  type="text" name="news_movie_uir_describe[]" lay-verify="required" autocomplete="off" placeholder="视频" class="layui-input"></textarea><input type="hidden" name="video_length[]" value="0" ></td>'
                                +'<td>'
                                    +'<button type="button"  class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>'
                                +'</td>'
                          +'</tr>'
                    $('#ossfile').append(str);
                });
                //删除
                $('.demo-delete').on('click', function(){
                    $(this).parent('td').parent('tr').remove()
                });
            },
    
            BeforeUpload: function(up, file) {
                check_object_radio();
                get_dirname();
                set_upload_param(up, file.name, true);
            },
    
            UploadProgress: function(up, file) {
                var d = document.getElementById(file.id);
                d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                var prog = d.getElementsByTagName('div')[0];
                var progBar = prog.getElementsByTagName('div')[0]
                progBar.style.width= 2*file.percent+'px';
                progBar.setAttribute('aria-valuenow', file.percent);
            },
    
            FileUploaded: function(up, file, info) {
                if (info.status == 200)
                {
                    layui.use(['layer'], function(){
                        var layer = layui.layer
                        layer.msg('上传成功', {
                            time: 2000,//3s后自动关闭
                        });
                    })
                    document.getElementById('upload-'+file.id).getElementsByTagName('td')[3].innerHTML = '<video id="videoPlayerNew'+file.id+'" width="100px" src="/'+'upload/'+get_uploaded_object_name(file.name)+'"></video><input type="hidden" name="news_movie_uir[]" value="'+'upload/'+get_uploaded_object_name(file.name)+'">'
                    var vid = document.getElementById("videoPlayerNew"+file.id+"");
                    vid.onloadedmetadata = function() {
                        document.getElementById('upload-'+file.id).getElementsByTagName('td')[4].innerHTML = '<textarea  type="text" name="news_movie_uir_describe[]" lay-verify="required" autocomplete="off" placeholder="视频" class="layui-input"></textarea><input type="hidden" name="video_length[]" value="'+vid.duration+'" >'
                    };
                }
                else
                {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
                } 
            },
    
            Error: function(up, err) {
                document.getElementById('console').appendChild(document.createTextNode("\nError xml:" + err.message));
            },
        },
        filters: {
            mime_types: [
                { title : "Video files", extensions : 'mp4,flv,avi,asf,wmv,mov,webm,mpeg4,ts,mpg,rm,rmvb,mkv' },
                { title : "Audio files", extensions : 'mp3,cd,wave,mpeg,midi,wma,yamaha,vqf,amr,ape,flac,aac'}
            ]
        }
    });
    
    uploader.init();
}

//上传到本地
function uploadLocat(url){
    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'selectfiles', 
        //multi_selection: false,
        container: document.getElementById('container'),
        flash_swf_url : 'lib/plupload-2.1.2/js/Moxie.swf',
        silverlight_xap_url : 'lib/plupload-2.1.2/js/Moxie.xap',
        unique_names:true,
        multipart: true,
        // multi_selection:true,
        // chunk_size: '10mb',
        container: document.getElementById('container'), // ... or DOM Element itself
        url: url,
        filters : {
            max_file_size : '1000mb'
            //,mime_types: [
            //    {title : "Image files", extensions : "jpg,gif,png"},
            //    {title : "Zip files", extensions : "zip"}
            //]
        },
    
        init: {

            PostInit: function() {
                // document.getElementById('ossfile').innerHTML = '';
                document.getElementById('postfiles').onclick = function() {
                uploader.start();
                return false;
                };
            },
            FilesAdded: function(up, files) {
                var str = ""
                plupload.each(files, function(file) {
                    str = '<tr id="upload-'+ file.id +'">'
                            +'<td><input  type="text" lay-verify="required" class="layui-input" name="moveName[]" value="'+file.name+'" ></td>'
                            +'<td>'+ (file.size/1024).toFixed(1) +'kb <input type="hidden" name="moveSize[]" value="'+(file.size/1024).toFixed(1)+'" lay-verify="required" ></td>'
                                +'<td><div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ')<b></b>'
                                +'<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>'
                                +'</div>'
                                +'<td></td>'
                                +'<td><textarea  type="text" name="news_movie_uir_describe[]" lay-verify="required" autocomplete="off" placeholder="视频" class="layui-input"></textarea><input type="hidden" name="video_length[]" value="0" ></td>'
                                +'<td>'
                                    +'<button type="button"  class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>'
                                +'</td>'
                          +'</tr>'
                    $('#ossfile').append(str);
                });
                //删除
                $('.demo-delete').on('click', function(){
                    $(this).parent('td').parent('tr').remove()
                });
            },
    
            UploadProgress: function(up, file) {
                var d = document.getElementById(file.id);
                d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                var prog = d.getElementsByTagName('div')[0];
                var progBar = prog.getElementsByTagName('div')[0]
                progBar.style.width= 2*file.percent+'px';
                progBar.setAttribute('aria-valuenow', file.percent);
            },
    
            Error: function(up, err) {
                document.getElementById('console').appendChild(document.createTextNode("\nError xml:" + err.message));
            },
            FileUploaded: function(up, file, info) {
                if (info.status == 200)
                {
                    layui.use(['layer'], function(){
                        var layer = layui.layer
                        layer.msg('上传成功', {
                            time: 2000,//3s后自动关闭
                        });
                    })
                    document.getElementById('upload-'+file.id).getElementsByTagName('td')[3].innerHTML = '<video id="videoPlayerNew'+file.id+'" width="100px" src="/'+info.response+'"></video><input type="hidden" name="news_movie_uir[]" value="'+info.response+'">'
                    var vid = document.getElementById("videoPlayerNew"+file.id+"");
                    vid.onloadedmetadata = function() {
                        document.getElementById('upload-'+file.id).getElementsByTagName('td')[4].innerHTML = '<textarea  type="text" name="news_movie_uir_describe[]" lay-verify="required" autocomplete="off" placeholder="视频" class="layui-input"></textarea><input type="hidden" name="video_length[]" value="'+vid.duration+'" >'
                    };
                }
                else
                {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
                } 
            }
        }
    });
    
    uploader.init();
}
