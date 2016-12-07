<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/21
 * Time: 11:52
 */
class DemoModel extends Model
{
    #测试查询（条件:？代替值，值用数组表示）
    public function testSelect()
    {
        $result = $this->table("test")->where("ID=? OR name=?", array('1', 'taoge'))->select();
        //$result = $this->table("test")->limit(0,5)->order('tel')->select();
        if (!empty($result)) {
            return $result;
        } else {
            echo "failed";
        }
    }

    #测试插入y(一行、一维数组，多行、多维数组)
    public function testAdd()
    {
        $add = $this->table("test")->field("ID,name,address,tel")->values(
            [
                array(7, 'aa', 'bb', '110'),
                array(8, 'aa', 'bb', '110'),
            ]
        )->add();
        if (!empty($add)) {
            echo "succeed";//test
            return $add;
        } else {
            echo "failed";
        }
    }

    #测试更新y（更新的字段用关联数组表示，字段名为键名，字段值为键值）
    public function testUpdate()
    {
        $up = $this->table("test")->where("ID=?", array("9"))->fieldValues(array('name' => 'ab', 'address' => 'ab'))->update();
        if (!empty($up)) {
            echo "succeed";//test
            return $up;
        } else {
            echo "failed";
        }
    }

    #测试删除y
    public function testDelete()
    {
        $de = $this->table("test")->where("ID=?", array("9"))->deletes();
        if (!empty($de)) {
            echo "succeed";//test
            return $de;
        } else {
            echo "failed";
        }
    }
}