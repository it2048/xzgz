<?php
/**
 * Created by PhpStorm.
 * User: sibenx
 * Date: 15/10/14
 * Time: 上午1:03
 */
class AppXzNative extends XzNative{
    /**
     * 实例化模型
     * @param string $classname
     * @return Authitem|void
     */
    public static function model($classname=__CLASS__)
    {
        return parent::model($classname);
    }

}