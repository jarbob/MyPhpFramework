<?php

/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/19
 * Time: 17:27
 * 单例化的mysql类：3私1公
 */
class MySqlDB implements i_DAO
{
    private $_host;
    private $_port;
    private $_user;
    private $_password;
    private $_charset;
    private $_dbname;
    private $_link;

    /**
     * MySqlDB constructor.
     * @param array $config
     */
    private function __construct($config = array())
    {
        $this->_initServer($config);//初始化服务器信息
        $this->_connectServer();//链接服务器
        $this->_setCharset();//设置字符集编码
        $this->_selectDB();//选择默认数据库
    }

    private function __clone()
    {
        echo "不能克隆该对象", "<br>";
        die();
    }

    private static $_instance;

    public static function getInstance($config = array())
    {
        if (!(static::$_instance instanceof static)) {
            static::$_instance = new static($config);
        }
        return static::$_instance;
    }

    private function _initServer($config)
    {
        $this->_host = isset($config['host']) ? $config['host'] : 'localhost';
        $this->_port = isset($config['port']) ? $config['port'] : '3306';
        $this->_user = isset($config['user']) ? $config['user'] : '';
        $this->_password = $config['password'];
        $this->_charset = isset($config['charset']) ? $config['charset'] : 'UTF8';
        $this->_dbname = isset($config['dbname']) ? $config['dbname'] : 'test';
    }

    private function _connectServer()
    {
        $connect_result = @mysql_connect("$this->_host:$this->_port", $this->_user, $this->_password);
        if ($connect_result) {
            $this->_link = $connect_result;
        } else {
            echo '数据库连接失败，请确认服务器信息';
            die();
        }
    }

    private function _setCharset()
    {
        $sql = "SET NAMES $this->_charset";
        $this->query($sql);
    }

    private function _selectDB()
    {
        $sql = "USE `$this->_dbname`";
        $this->query($sql);
    }

    /**
     * 执行SQL语句
     * @param string $sql
     * @return mixed 执行结果。查询类的SQL(select, show, desc),成功返回结果集资源，失败返回false。非查询类(insert, delete, update)，成功返回true，失败返回false.
     */
    public function query($sql)
    {
        $query_result = @mysql_query($sql, $this->_link);
        if (false == $query_result) {
            echo "SQL执行失败:", "<br>";
            echo "错误的SQL:", "<br>", $sql, "<br>";
            echo "错误的消息为:", "<br>", mysql_errno($this->_link), "<br>";
            die();
        } else {
            return $query_result;
        }
    }

    /**
     * @param string $sql 通常为:select * from ...
     * @return array
     */
    public function fetchRow($sql)
    {
        $result = $this->query($sql);
        $row = @mysql_fetch_assoc($result);
        @mysql_free_result($result);
        return $row;
    }

    /**
     * @param string $sql 通常为:select count(*) from ...
     * @return string 如果没有值就返回NULL
     */
    public function fetchOne($sql)
    {
        $result = $this->query($sql);
        $row = @mysql_fetch_row($result);
        @mysql_free_result($result);
        if ($row)
            return $row[0];
        else
            return NULL;
    }

    /**
     * @param string $sql 通常为:select * from ... where ..like 'han%'
     * @return array
     */
    public function fetchAll($sql)
    {
        $result = $this->query($sql);
        $rows = array();
        while ($row = @mysql_fetch_assoc($result))
            $rows[] = $row;
        @mysql_free_result($result);
        return $rows;
    }

    /*
     * 关闭当前数据库连接, 一般无需使用. 连接会随php脚本结束自动关闭
     */
    /*public function close()
    {
        return @mysql_close($this->_link);
    }*/

    /**
     * 防止sql注入：转义字符串，在模型中使用
     * @param string $str 带转义的字符串
     * @return string 带引号包裹的转义后的字符串
     */
    public function escapeString($str = '')
    {
        return "'" . mysql_real_escape_string($str, $this->_link) . "'";
    }

}