```
use PHPQrcode\Qrcode;

$value = "http://www.baidu.com";//二维码内容
$errorCorrectionLevel = 'L';//容错级别
$matrixPointSize = 10;//生成图片大小

$time=date("y-m");
$com = date("d");
$dir="Data/Qrcode/".$time .'/'. $com;
//生成二维码图片
if(!file_exists($dir)){
    //如果不创建，递归创建
    mkdir($dir,0777,true);
}
$file_name = $dir."/" . date("YmdHis").mt_rand(1000,9999).".png";
Qrcode\QRcode::png($value, $file_name, $errorCorrectionLevel, $matrixPointSize, 2);
$logo = "logo.png";//准备好的logo图片
$QR = $file_name;//已经生成的原始二维码图
if ($logo !== FALSE) {
    $QR = imagecreatefromstring(file_get_contents($QR));
    $logo = imagecreatefromstring(file_get_contents($logo));
    $QR_width = imagesx($QR);//二维码图片宽度
    $QR_height = imagesy($QR);//二维码图片高度
    $logo_width = imagesx($logo);//logo图片宽度
    $logo_height = imagesy($logo);//logo图片高度
    $logo_qr_width = $QR_width / 5;
    $scale = $logo_width/$logo_qr_width;
    $logo_qr_height = $logo_height/$scale;
    $from_width = ($QR_width - $logo_qr_width) / 2;
    //重新组合图片并调整大小
    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);
}
//输出图片
//header("Content-type: image/png");
//imagepng($QR);
//imagepng($QR);
echo $file_name;
```