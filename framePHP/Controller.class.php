<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/27
 * Time: 15:09
 */
class Controller
{
    #定义属性，接收模块变量，用于加载视图
    protected $controllers;
    protected $views;

    //构造函数
    public function __construct($controller)//Route类中实例化DemoController传入$controller
    {
        if (isset($controller)) {
            $this->controllers = $controller;//模块名
            #实例化视图模块
            $this->views = new View($this->controllers);
        }

    }

    //分配变量
    function assign($name, $value)
    {
        $this->views->assign($name, $value);
    }

    //传入要加载的视图
    function view($html)
    {
        $this->views->view($html);
    }

    //调用加载视图的函数实现C-V通信
    public function __destruct()//析构函数，对象销毁时自动调用，不允许有任何参数;
    {
        $this->views->loadViews();
    }

}