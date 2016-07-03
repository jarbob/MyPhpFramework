<?php

/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/20
 * Time: 2:35
 * 后台的admin的操作模型类
 */
class AdminModel extends Model
{
    /**
     * 通过用户名和密码检测合法性
     * @param $admin_name
     * @param $admin_password
     * @return bool 检测的结果 成功返回数组，失败返回false
     */
    public function checkLogin($admin_name, $admin_password)
    {
        $escape_name = $this->_dao->escapeString($admin_name);
        $escape_password = $this->_dao->escapeString($admin_password);
        $sql = "select * from `jb_admin` where `admin_name`=$escape_name and `admin_password`=md5($escape_password)";
        return $this->_dao->fetchRow($sql);
    }

    /**
     * 通过id和password校验合法性
     * @param string $md5_id
     * @param string $md5_2_password
     */
    public function checkRemember($md5_id = '', $md5_2_password = '')
    {
        $sql = "select * from `jb_admin` where md5(concat(`admin_id`,'SALT')) = '$md5_id' and md5(concat(`admin_password`,'SALT'))
 = '$md5_2_password'";
        return $this->_dao->fetchRow($sql);
    }
}