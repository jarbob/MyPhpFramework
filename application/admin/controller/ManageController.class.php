<?php
/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/19
 * Time: 23:50
 * 后台的管理中心控制器
 */
class ManageController extends ModuleController{
    public function indexAction(){
        require "./application/admin/view/index.html";
    }
   public function topAction(){
       require "./application/admin/view/top.html";
   }public function menuAction(){
       require "./application/admin/view/menu.html";
   }public function dragAction(){
       require "./application/admin/view/drag.html";
   }public function mainAction(){
       require "./application/admin/view/main.html";
   }
}