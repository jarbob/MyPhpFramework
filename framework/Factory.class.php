<?php
/**
 * Created by PhpStorm.
 * User: jiangbo
 * Date: 2016/1/19
 * Time: 1:56
 * 工厂模型，用来得到单例模型
 */

class Factory{
    /**
     * @param string $modelName 模型名称
     * @return “返回对应的模型对象”
     */
    public static function M($modelName=''){
        static $modelList = array();//用来存储所有的模型对象
        if(!isset($modelList[$modelName])){
            //该模型类的对象不存在，则实例化
            $modelClassName = $modelName."Model";
            $modelList[$modelName] = new $modelClassName();
        }
        return $modelList[$modelName];
    }
}