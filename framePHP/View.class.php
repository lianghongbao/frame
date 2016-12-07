<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/27
 * Time: 14:22
 */
class View
{
    protected $variables;
    protected $controllers;
    protected $loadViews;

    //构造函数，初始化
    public function __construct($controller)
    {
        if(isset($controller)){
            $this->variables   = array();
            $this->controllers = $controller;
        }

    }

    // 分配变量，赋值html模版
    function assign($name, $value)
    {
        $this->variables[$name] = $value;
    }

    //传入需要加载的视图
    function view($view)
    {
        $this->loadViews = $view;
    }

    //加载视图
    function loadViews()
    {
        //var_dump($this->variables);
        extract($this->variables);//从数组中将变量导入到当前符号表，键名->变量名，键值->变量值。//html页面的变量
        $viewPath = APP_PATH . VIEW_PATH . $this->controllers . '/' . $this->loadViews . '.html';
        //print_r($this->variables);
        //echo $viewPath;
        if (file_exists($viewPath)) {
            require "$viewPath";
        } else {
            exit('Unable to load the requested file: ' . $viewPath);
        }
    }


}