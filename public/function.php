<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/1
 * Time: 11:56
 */

function M($name)
{
    static $class = 'Model';
    if (!empty($name)) {
        $nameClass = $name . $class;
        $model     = new $nameClass;
        return $model;
    }
}