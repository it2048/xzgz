<?php
/**
 * Created by PhpStorm.
 * User: xiongfanglei
 * Date: 14-11-27
 * Time: 下午6:26
 */

class AppRsAdmin extends RsAdmin {

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