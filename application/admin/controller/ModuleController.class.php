<?php

/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/23
 * Time: 14:19
 * 后台登录的模块控制基类
 */
class ModuleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->_startSession();
        $this->_isLogin();
    }

    //开启session,公用方法
    protected function _startSession()
    {
        session_start();
    }

    /**
     * //特例列表，校验动作，防止浏览器因为父子调用构造函数导致的循环重定向
     * //定义特例列表 eg: 控制器名 => 动作列表名
     */
    protected function _isLogin()
    {
        $non_check = array(
            'admin' => array('login', 'check', 'captcha')
        );
        //判断控制器是否在列表 且 动作是否在列表
        if (isset($non_check[CONTROLLER]) && in_array(ACTION, $non_check[CONTROLLER])) {
            //存在于列表，则不需要跳转了
            return;
        }
        //判断是否具有登录凭证
        if (isset($_SESSION['admin'])) {
            $m_admin = Factory::M('Admin');
            //判断是否存在 且 可以校验
            if (isset($_COOKIE['admin_id']) && isset($_COOKIE['admin_password'])) {
                if ($result = $m_admin->checkRemember($_COOKIE['admin_id'], $_COOKIE['admin_password'])) {
                    //记录了登录状态
                    $_SESSION['admin'] = $result;
                } else {
                    //没有通过
                    $this->_jumpUrl("index.php?m=admin&c=admin&a=login");
                }
            }
        }
    }
}