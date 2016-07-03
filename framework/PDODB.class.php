<?php

/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/24
 * Time: 1:00
 * dao层使用dao扩展封装实现
 */
class PDODB implements i_DAO
{
    private $_host;
    private $_port;
    private $_user;
    private $_password;
    private $_charset;
    private $_dbname;

    private $_dsn;
    private $_option;
    private $_pdo;


    /**
     * PDODB constructor.
     * @param array $config
     */
    private function __construct($config = array())
    {
        $this->_initServer($config);
        $this->_newPDO();
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

    private function _newPDO()
    {
        //设置参数
        $this->_setDSN();//设置数据源参数
        $this->_setOption();//设置选项
        $this->_getPDO();//得到PDO对象
    }

    private function _setDSN()
    {
        $this->_dsn = "mysql:host=$this->_host;port=$this->_port;dbname=$this->_dbname";
    }

    private function _setOption()
    {
        $this->_option = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "set names $this->_charset"
        );
    }

    private function _getPDO()
    {
        $this->_pdo = new PDO($this->_dsn, $this->_user, $this->_password, $this->_option);

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
    //执行方法，适用的场景
    private static $_queryStr = array(
        "select",
        "show",
        "desc"
    );
    public function query($sql = '')
    {
        //使用正则过滤，分别使用query和exec

        foreach (static::$_queryStr as $str){

            if (preg_match("/^\s*".$str.".*?/i",$sql)){
                //查询类 返回结果集对象
                $result = $this->_pdo->query($sql);
            }else{
                //非查询类 返回bool
                $result = $this->_pdo->exec($sql) !== false;//有可能是0
            }
            //如果执行失败，报错
            if($result === false){
                $error_info = $this->errorInfo();
                echo "SQL执行失败:", "<br>";
                echo "错误的SQL:", "<br>", $sql, "<br>";
                echo "错误的消息为:", "<br>", $error_info[2], "<br>";
                die();
            }else{
                return $result;
            }
            break;
        }
    }

    public function fetchAll($sql = '')
    {
        $result = $this->query($sql);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        $result->closeCursor();
        return $rows;
    }

    public function fetchRow($sql = '')
    {
        $result = $this->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $result->closeCursor();
        return $row;
    }

    public function fetchOne($sql = '')
    {
        $result = $this->query($sql);
        $string = $result->fetchColumn();
        $result->closeCursor();
        return $string;
    }

    public function escapeString($str = '')
    {
        return $this->_pdo->quote($str);
    }
}