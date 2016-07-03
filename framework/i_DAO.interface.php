<?php
/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/24
 * Time: 1:05
 * 与mysql接口一致(模型层调用一致)，利用interface
 */
interface i_DAO{
    //获取与前DAO的接口
    public static function getInstance($config = array());
    //执行sql的方法
    public function query($sql = '');
    //获取全部数据
    public function fetchAll($sql = '');
    //获取一行数据
    public function fetchRow($sql = '');
    //获取一个数据
    public function fetchOne($sql = '');
    //转义sql，防止注入
    public function escapeString($str = '');

}