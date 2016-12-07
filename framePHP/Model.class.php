<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/23
 * Time: 16:04
 */
class Model extends Sql
{
    # 构造函数｜连接数据库//子类继承父类的方法，实例化子类即实现连接数据库
    public function __construct()
    {
        $this->connection(SERVER_NAME, DB_NAME, USER_NAME, USER_PASSWORD);
    }

}