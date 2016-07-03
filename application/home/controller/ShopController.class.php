<?php
/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/19
 * Time: 23:06
 * 前台的控制器，默认
 */
class ShopController{
    public function indexAction(){
        require './application/home/view/index.html';
    }
}