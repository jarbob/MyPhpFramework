<?php
/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/19
 * Time: 13:17
 *前端控制器，也叫请求分发器、入口文件
 *-请求分发是该文件的核心功能
 * url：index.php?c=Match&a=list
 *      index.php?c=Match&a=delete&m_id=2
 *      index.php?c=Team&a=info&t_id=23
 *      index.php?m=test&c=Match&a=list
 *      index.php?m=admin&c=Match&a=list
 * ::默认控制器和默认动作（没有c和a,m）
 * a(action)和c(controller),m(module)叫做请求分发参数
 */

//自动加载
spl_autoload_register("jbAutoload");
//类与类文件地址映射列表，保证仅仅定义一次。因为Autoload会被多次调用
static $class_list = array(
    'Factory' => './framework/Factory.class.php',
    'Model' => './framework/Model.class.php',
    'MySqlDB' => './framework/MySqlDB.class.php',
    'Controller' => './framework/Controller.class.php',
    'i_DAO' => './framework/i_DAO.interface.php',
    'PDODB' => './framework/PDODB.class.php',
    'Captcha' => './framework/tool/Captcha.class.php'
);
function jbAutoload($name=""){
    //映射表加载
    $class_list = $GLOBALS['class_list'];
    if(isset($class_list[$name])){
        require $class_list[$name];
    }
    //规则加载
    //模型加载
    elseif('Model' == substr($name,-5)){
        require "./application/".MODULE."/model/".$name.".class.php";
    }
    //控制器加载
    elseif("Controller" == substr($name,-10)){
        require "./application/".MODULE."/controller/".$name.".class.php";
    }
}
//模块分发定义
$default_module = 'home';
$current_module = isset($_GET['m'])?$_GET['m']:$default_module;
define("MODULE",$current_module);
//控制器分发定义
$default_controller = 'shop';
$current_controller = isset($_GET['c']) ? $_GET['c'] : $default_controller;
define('CONTROLLER', $current_controller);
//动作分发定义
$default_action = "index";
$current_action = isset($_GET['a']) ? $_GET['a'] : $default_action;
define('ACTION', $current_action);

//分发控制
$controller_class_name = CONTROLLER."Controller";
//require "./application/".MODULE."/controller/".$controller_class_name.".class.php";
$Controller = new $controller_class_name();
//分发动作
$action_method_name = ACTION."Action";
$Controller->$action_method_name();
?>