<?php
/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/19
 * Time: 1:02
 * 基础模型类
 */


class Model{
    /**
     * DAO : captcha access object
     */
    protected $_dao;//存储实例化好的数据库对象

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->_initDAO();//初始化基础模型
    }

    protected function _initDAO(){
        //require_once "./framework/MySqlDB.class.php";
        $config = array(
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'dbname' => 'project'
        );
        //$this->_dao = MySqlDB::getInstance($config);//调用mysqldb
        $this->_dao = PDODB::getInstance($config);//调用pdo
    }

}