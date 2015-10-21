<?php
/**
 * Created by PhpStorm.
 * User: sibenx
 * Date: 15/10/20
 * Time: 上午9:34
 */

class AppXzAdvert extends XzAdvert{
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