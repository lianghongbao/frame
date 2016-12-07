<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/23
 * Time: 10:08
 */
class Route
{
    protected $con;
    protected $met;

    // 路由处理
    public function __construct()
    {
        //http://192.168.56.102/testkj/demo/testModel
        //获取控制器名：
        /*$url=$_SERVER["REQUEST_URI"];
        $array=explode("/",$url);
        $this->con=$array[2];
        $this->met=$array[3];*/

        //GET方式
        //http://192.168.56.102/testkj/index.php?controller=xxx&method=xxx
        $this->con = $_GET['controller'];  //controller=>demo
        if(!empty($this->con)){
            if(filter_var($this->con,FILTER_SANITIZE_STRING)){
                //preg_match("/[a-z]/i",$con)正则表达式匹配
                $cont=$this->con;
            }else{
                echo "输入了非法字符！";
            }
        }

        $cName = $cont . 'Controller';//$cName=>demoController(类名、方法名不区分大小写)

        //获取模型名
        $mName = $cont . 'Model';//DemoModel

        //加载控制器
        $cPath = CONTROLLER_PATH . "$cont/" . $cName . '.php';//$cPath=>application/controller/Demo/demoController.php
        require "$cPath";//加载controller文件*/

        //加载模型
        $mPath = MODEL_PATH . "$cont/" . $mName . '.php';//application/model/Demo/DemoModel.php
        require "$mPath";//require 'application/model/DemoModel.php';

        //获取方法名
        $this->met = $_GET['method'];//method=>index
        if(!empty($this->met)){
            if(filter_var($this->met,FILTER_SANITIZE_STRING)){
                //preg_match('/\w/',$met)
                $method=$this->met;
            }else{
                echo "输入了非法字符！";
            }
        }else{
            echo "method not exists!";
            //header('location:http://localhost/testkj/application/index.php');
        }

        //实例化控制器,并传入Controller类构造方法的参数
        $controller = new $cName($cont);//demoController
        $controller->$method();

    }


}