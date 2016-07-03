<?php

/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/20
 * Time: 16:53
 */
class Controller
{
    public function __construct()
    {
        $this->_setContentType();
    }


    /**
     * 设置编码
     * @param string $char_set eg:utf-8 ,gbk..
     */
    protected function _setContentType($char_set = 'utf-8')
    {
        header("Content-type:text/html;charset=$char_set");
    }

    /**
     * 跳转链接方法:立即跳转和延时跳转，默认为立即跳转，只需设置url即可。
     * @param $url .跳转的链接
     * @param $msg .跳转到位置，如首页，后台主页。。
     * @param int $sleep .为0表示立即跳转，不为0表示$sleep秒后跳转
     */
    protected function _jumpUrl($url = 'index.php', $msg = '', $sleep = 0)
    {
        //立即跳转
        if ($sleep == 0)
            header("location:$url");
        //延时跳转
        else{
            header("refresh:$sleep;url=$url");
            echo "<span id='php_t'>$sleep</span>", "秒后跳转到", $msg, ",如果没有跳转,您可以<a href='$url'>点击</a>此处进行跳转","<script tpye='text/javascript'>var php_t = $sleep;setInterval('php_change()',900);function php_change(){if(php_t > 0)document.getElementById('php_t').innerHTML = php_t--;}</script>";

        }
        die();
    }
}