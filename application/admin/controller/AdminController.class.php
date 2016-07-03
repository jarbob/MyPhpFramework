<?php

/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/20
 * Time: 0:57
 * 后台管理员相关的控制器，登录，退出，注册。。
 */
class AdminController extends ModuleController
{
    //登录页面加载
    public function loginAction()
    {
        require "./application/admin/view/login.html";
    }

    /**
     * 生成验证码
     */
    public function captchaAction(){
        $t_code = new Captcha();
        $t_code->makeCode();
    }

    //登录检测
    public function checkAction()
    {
        //收集数据
        $admin_name = isset($_POST['username']) ? $_POST['username'] : '';
        $admin_password = isset($_POST['password']) ? $_POST['password'] : '';
        $code = isset($_POST['code']) ? $_POST['code'] : '';
        $t_captcha = new Captcha();
        if(!$t_captcha->checkCode($code)){
            echo "验证码错误！<br>";
            $this->_jumpUrl("index.php?m=admin&c=admin&a=login", "登录首页", 4);
        }

        //利用模型处理
        $m_admin = Factory::M('Admin');
        $result = $m_admin->checkLogin($admin_name, $admin_password);

        //做出处理
        if ($result) {
            // 保存凭证
            $_SESSION['admin'] = $result;
            //记录登录状态
            //是否需要
            if (isset($_POST['isRememberUsername'])) {
                setcookie('admin_id', md5($result['admin_id'] . 'SALT'), time() + 30 * 24 * 3600, '/');
                setcookie('admin_password', md5($result['admin_password'] . 'SALT'), time() + 30 * 24 * 3600, '/');
            }
            //立即跳转
            $this->_jumpUrl("index.php?m=admin&c=manage&a=index");
        } else {
            echo "登录失败!<br>";
            $this->_jumpUrl("index.php?m=admin&c=admin&a=login", "登录首页", 4);
        }
    }
    //注销
    public function logoutAction()
    {
        unset($_SESSION['admin']);
        $this->_jumpUrl("index.php?m=admin&c=admin&a=login");
    }
}