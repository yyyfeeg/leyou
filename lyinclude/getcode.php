<?php 
/*
 * 验证码
 */
session_start();
if (function_exists('imagegif')){
	$header = 'Content-type: image/gif';
	$fun = 'imagegif';
}
elseif (function_exists('imagepng')){
	$header = 'Content-type: image/png';
	$fun = 'imagepng';
}
elseif (function_exists('imagejpeg')){
	$header = 'Content-type: image/jpeg';
	$fun = 'imagejpeg';
}
//生成验证码图片 
Header($header);
srand((double)microtime()*1000000);//播下一个生成随机数字的种子，以方便下面随机数生成的使用
//$numbers = range(A,Z);
//shuffle($numbers);
//session_start();//将随机数存入session中
$_SESSION['code']="";
$imagewidth = 140;//图片宽度
$imageheight = 40;//图片高度
$fonsize=28;//字体大小
$font_x=28;//文字显示的（X）位置
$font_y=($imageheight+$fonsize)/2;//文字显示的（Y）位置
$im = imagecreate($imagewidth,$imageheight); //制定图片背景大小

$black = ImageColorAllocate($im, 0,0,0); //设定三种颜色
$white = ImageColorAllocate($im, 255,255,255); 
$gray = ImageColorAllocate($im, 255,255,255);
imagefill($im,0,0,$gray); //采用区域填充法，设定（0,0）

$authnum=rand(1000,10000);
//将四位整数验证码绘入图片 
// $authnum=$numbers[2].substr($authnum,0,1).substr($authnum,3);

$_SESSION['code']=$authnum;
//imagestring($im, 5, 18, 3, $authnum, $black);//生成的文字小小
imagettftext($im, $fonsize, 0, $font_x, $font_y, $black, './arial.ttf', $authnum);
// 用 col 颜色将字符串 s 画到 image 所代表的图像的 x，y 座标处（图像的左上角为 0, 0）。
//如果 font 是 1，2，3，4 或 5，则使用内置字体

//随机的画几条线段
for($i = 0; $i < 6; $i++)
{
   imageline($im, rand()%$imagewidth, rand()%$imageheight, rand()%$imagewidth, rand()%$imageheight, $black);
}

for($i=0;$i<200;$i++) //加入干扰象素 
{ 
	$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
	imagesetpixel($im, rand()%$imagewidth, rand()%$imageheight, $randcolor); 
} 
$fun($im); 
ImageDestroy($im); 
?>