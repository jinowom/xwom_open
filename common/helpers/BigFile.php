<?php
namespace common\helpers;

class BigFile {
    public function chuli($get=null,$post=null,$file=null)
    {
        // $post = 每次调用接口时传入的post参数 $file = 每次调用接口时 传入的文件
        if(empty($get)) return "{'error':'1','success':'1','desc':'无法识别您操作'}";
        // 没有参数
        if($get == 'index') return $this->index($post,$file);
        // 处理参数
        if($get == 'check') return $this->check($post);
        // 处理参数
        if($get == 'merge') return $this->merge($post);
        // 合并文件
    }
    // 处理文件
    public function index($post,$file)
    {
        // $post['chunk']=isset($post['chunk'])?$post['chunk']:0;

        if (empty($post)&&empty($file)) {
            return json_encode(['success'=>'1','error'=>'2','desc'=>'md5和文件内容不能为空']);
        }
        // 建立临时目录存放文件-以MD5为唯一标识
        $dir = 'upload/' . $post['md5value'];

        if (!file_exists($dir)) mkdir ($dir,0777,true);

        // 移入缓存文件保存
        $res = move_uploaded_file($file["file"]["tmp_name"], $dir.'/'.$post["chunk"]);
        if ($res) {
            return json_encode(['success'=>'1','error'=>'0','desc'=>'']);
        }

    }
    // 切片 检查是否存在断点
    public function check($post)
    {
        // $post = 文件数据
        if (empty($post)) {
            return json_encode([]);
        }
        // 通过MD5唯一标识找到缓存文件
        $file_path = 'upload/' .$post['md5'];

        // 有断点
        if (file_exists($file_path)) {

            // 遍历成功的文件
            $block_info = scandir($file_path);

            // 除去无用文件
            foreach ($block_info as $key => $block) {
                if ($block == '.' || $block == '..') unset($block_info[$key]);
            }

            return json_encode(['block_info' => $block_info]);
        }
        // 无断点
        else {
            return json_encode([]);
        }
    }

    // 合并文件
    public function merge($post)
    {
        // $post 文件相关数据

        // 找出分片文件
        $dir = 'upload/'.$post['md5'];

        // 获取分片文件内容
        $block_info = scandir($dir);

        // 除去无用文件
        foreach ($block_info as $key => $block) {
            if ($block == '.' || $block == '..') unset($block_info[$key]);
        }

        // 数组按照正常规则排序
        natsort($block_info);

        // 定义保存文件
        $save_file = 'upload/' . $post['fileName'];

        // 没有？建立
        if (!file_exists($save_file)) fopen($post['fileName'], "w");

        // 开始写入
        $out = @fopen($save_file, "wb");

        // 增加文件锁
        if (flock($out, LOCK_EX)) {
            foreach ($block_info as $b) {
                // 读取文件
                if (!$in = @fopen($dir.'/'.$b, "rb")) {
                    break;
                }

                // 写入文件
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }

                @fclose($in);
                @unlink($dir.'/'.$b);
            }
            flock($out, LOCK_UN);
        }
        @fclose($out);
        @rmdir($dir);
        // 检测文件
        if (is_readable(__DIR__.'/'.$post['fileName']) == true) {
            @unlink(__DIR__."/".$post['fileName']);
        }
        // 删除在根目录转换文件时留下的文件
        return json_encode(["code"=>"0"]);//随便返回个值，实际中根据需要返回
    }
}
 ?>