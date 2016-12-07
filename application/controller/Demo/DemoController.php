<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/21
 * Time: 9:34
 */
class DemoController extends Controller
{

    function index()
    {
        $data['title'] = 'First Title';
        $data['list'] = array('A', 'B', 'C', 'D');

        $this->assign('data',$data);
        $this->view('index');
    }

    function testModel()
    {
        #实例化
        //$m =new DemoModel();
        $m=M("Demo");
        #测试查询
        $result=$m->testSelect();
        while ($row = $result->fetch())
        {
            //fetch()默认值为fetch_style是FETCH_ASSOR和FETCH_NUM的结合。即：FETCH_BOTH。下面为测试
            echo "ID：" . $row[0] . "<br/>";
            echo "姓名：" . $row['name'] . "<br/>";
            echo "地址：" . $row[2] . "<br/>";
            echo "电话：" . $row['tel'] . "<br/>";
        }
        #测试增加
        //$m->testAdd();
        #测试更新
        //$m->testUpdate();
        #测试删除
        //$m->testDelete();

        #赋值模板
        //$this->assign('result',$re);
        $this->assign('list','Hello World !');


        #传入要加载的模板
        $this->view('testModel');

    }


    function testForm()
    {
        $this->view('upload');
    }

}
