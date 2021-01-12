<?php
class CustomVerify{
    /**
    +----------------------------------------------------------
    * 生成验证码
    +----------------------------------------------------------
    * @static
    * @access public
    +----------------------------------------------------------
    * @param int $len  验证码字符数
    * @param int $font_size  验证码字体大小
    * @param string $name  session名称
    * @param int $width  图片长度
    * @param int $height  图片高度
      +----------------------------------------------------------
    * @return void
      +----------------------------------------------------------
    */
    static function creatVerify($len=4,$font_size=36,$name='zhaolei',$width='220',$height='80'){
        if($width=='') $width=($font_size+5)*($len+1);
        if($height=='') $height=($font_size)*3;
        $chars='123456789bcdefhkmnrstuvwxyABCDEFGHKMNPRSTUVWXY';
        $str='';
        for($i=0;$i<$len;$i++){
            $str .= substr($chars,mt_rand(0,strlen($chars)-1),1);
        }
        $_SESSION[$name]=$str;//写入session
        for($num=0;$num<10;$num++){
            ob_start();
            $image=imagecreatetruecolor($width,$height);//创建图片
            $bg_color=imagecolorallocate($image,255,255,255);//设置背景颜色
            $border_color=imagecolorallocate($image,100,100,100);//设置边框颜色
            $text_color=imagecolorallocate($image,0,0,0);//设置验证码颜色
            imagefilledrectangle($image,0,0,$width-1,$height-1,$bg_color);//填充图片背景色
            imagerectangle($image,0,0,$width-1,$height-1,$border_color);//填充图片边框颜色
            for($i=0;$i<5;$i++){
                $line_color=imagecolorallocate($image,rand(0,255),rand(0,255),rand(0,255));//干扰线颜色
                imageline($image,rand(0,$width),0,$width,$height,$line_color);//画一条线段
            }
            for($i=0;$i<500;$i++){
                $dot_color=imagecolorallocate($image,rand(0,255),rand(0,255),rand(0,255));//干扰点颜色
                imagesetpixel($image,rand()%$width,rand()%$height,$dot_color);//画一个像素点
            }
            for($i=0;$i<$len;$i++){
                imagettftext($image,$font_size,rand(-3,3),$font_size/2+($font_size+5)*$i,$height/1.5-rand(2,3),$text_color,'font.ttf',$str[$i]);//用规定字体向图像写入文本
            }
            imagegif($image);
            imagedestroy($image);
            $imagedata[] = ob_get_contents();
            ob_clean();
        }
        require('GIFEncoder.class.php');
        $gif = new GIFEncoder($imagedata);
        ob_clean();//防止出现'图像因其本身有错无法显示'的问题
        header('Content-type:image/gif');
        echo $gif->GetAnimation();
    }
}
//调用示例
session_start();
CustomVerify::creatVerify(4,36);
?>