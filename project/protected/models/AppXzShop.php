<?php
/**
 * Created by PhpStorm.
 * User: xiongfanglei
 * Date: 15-3-20
 * Time: 下午1:50
 */
class AppXzShop extends XzShop
{
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