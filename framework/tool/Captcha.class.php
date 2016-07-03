<?php

/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/25
 * Time: 20:35
 */
class Captcha
{
    /**
     * 生成验证码
     * @param int $code_length
     */
    public function makeCode($code_length = 4)
    {
        $char_list = 'ABCDEFGHIJKLMNPQRSTUVWXYZ12345678';
        $list_len = strlen($char_list);
        //$code_length = 4;
        $code = '';
        for ($i = 1; $i <= $code_length; ++$i) {
            $rand_index = mt_rand(0, $list_len - 1);
            $code .= $char_list[$rand_index];
        }
        //echo $code;
        @session_start();
        $_SESSION['captcha_code'] = $code;

        //随机背景图
        $bg_file = './framework/tool/captcha/captcha_bg' . mt_rand(1, 4) . ".jpg";
        $image = imagecreatefromjpeg($bg_file);
        if (mt_rand(1, 2) == 1) {
            $code_color = imagecolorallocate($image, 40, 40, 40);//黑
        } else {
            $code_color = imagecolorallocate($image, 120, 20, 200);
        }
        $font = 5;
        //画布宽高
        $image_w = imagesx($image);
        $image_h = imagesy($image);
        //字体的宽高
        $font_w = imagefontwidth($font);
        $font_h = imagefontheight($font);
        //字符串宽高
        $code_w = $font_w * $code_length;
        $code_h = $font_h;
        //位置x,y
        $str_x = ($image_w - $code_w) / 2;
        $str_y = ($image_h - $code_h) / 2;
        imagestring($image, $font, $str_x, $str_y, $code, $code_color);
        header('content-type:image/jpeg');
        //输出资源
        imagejpeg($image);
        //销毁图片资源
        imagedestroy($image);
    }

    /**
     * 检查验证码是否正确
     * @param string $request_code
     * @return bool
     */
    public function checkCode($request_code = '')
    {
        @session_start();
        $result = isset($request_code) && isset($_SESSION['captcha_code']) && $_SESSION['captcha_code'] == strtoupper($request_code);
        //删除验证码缓存
        if (isset($_SESSION['captcha_code'])){
            unset($_SESSION['captcha_code']);
        }
        return $result;
    }

}