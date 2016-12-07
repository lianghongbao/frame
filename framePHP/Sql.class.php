<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/23
 * Time: 10:12
 */
class Sql
{
    public static $cn;//连接
    private       $field       = "*";//默认查询所有
    private       $tableName;
    private       $where       = "";
    private       $order       = "";
    private       $limit       = "";
    private       $whereValuesCount;
    private       $whereValues = array();
    //---------update()要用到的成员属性-----------
    private        $upValues;//要更新字段的值
    private        $upCount;//要更新参数数量
    private        $fieldValues;//更新 列=？
    //---------add()要用到的成员属性--------------
    private        $first;//第一个插入的值
    private        $countOut;//外层循环
    private        $countIn;//内层循环
    private        $values;//要插入的值（组装）
    private        $valuesValues;//保存插入的值
    private static $num = 0;//add()绑定?号的索引123...


    #连接数据库
    public function connection($serverName, $dbName, $userName, $userPassword)
    {
        try {
            if (is_object(self::$cn)) {
                return self::$cn;
            } else {
                $conn = new PDO("mysql:host=$serverName;dbname=$dbName", $userName, $userPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//设置 PDO 错误模式为异常
                self::$cn = $conn;//类内部访问静态属性用self::
                return self::$cn;
            }
        } catch (PDOException $e) {
            echo "数据库连接失败！" . "<br>" . $e->getMessage();
        }
        $conn = null;

    }

    //要查询或插入的列(数组/字符串)
    function field($field)
    {
        $this->field = is_array($field) ? '\'' . implode(' \',\' ', $field) . '\'' : $field;
        return $this;
    }

    //表名
    function table($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    //排序
    function order($order)
    {
        $this->order = "order by" . " " . $order;
        return $this;
    }

    //条件
    function where($string,$values=array())
    {
        $wh = substr_count($string,'?');
        if(!empty($values)){
            $this->whereValues=array_values($values);
            $this->whereValuesCount = count($values);
            if($wh == $this->whereValuesCount){
                $this->where = "where" . " " .$string;
                //echo $this->where;
                return $this;
            }
        }
    }


    /* //条件
     function where($string, $values=array())
     {
         $wh = substr_count($string, '?');//统计?个数
         if (!empty($values)) {
             $this->whereValues=array_values($values);
             $this->whereValuesCount = count($values);
             if ($wh == $this->whereValuesCount) {
                 for ($i = 0; $i < $this->whereValuesCount; $i++) {
                     // 用addslashes转义，正则替换，一次只替换一个
                     $string = preg_replace('/\?/', "'" . addslashes($this->whereValues[$i]) . "'", $string, 1);
                 }
                 $this->where = "where" . " " . $string;
                 //echo $this->where;
                 return $this;
             }
         }
     }*/

    //限制输出
    function limit($start, $limit = null)
    {
        $this->limit = "LIMIT" . " " . $start;//一个参数
        if (!is_null($limit)) {
            $this->limit .= ",$limit";//如果limit有值，可以限制记录数目。
        }
        return $this;
    }

    //更新 列=？组装
    function fieldValues($fieldValues = array())
    {
        if (!empty($fieldValues)) {
            $this->upValues    = array_values($fieldValues);
            $fields            = array_keys($fieldValues);
            $this->upCount     = count($fieldValues);
            $this->fieldValues = implode('=?,', $fields) . '=?';//用占位符 =?，分隔字段名
        }
        return $this;
    }

    //插入的值
     function values($values = array())
    {
        if (!empty($values)) {
            $this->first        = current($values);//第一个数组(values($values),($values))
            $this->countOut     = count($values);//控制重复次数和循环次数。
            $this->valuesValues = $values;//保存插入的值
            if (is_array($this->first)) {
                $this->countIn = count($this->first);
                // 插入多行
                $firstValues  = substr(str_repeat('?,', count($this->first)), 0, -1);//截掉最后多余的一个逗号
                $this->values = substr(str_repeat($firstValues . '),(', count($values)), 0, -3);
            } else {
                //插入一行
                $this->values = substr(str_repeat('?,', count($values)), 0, -1);
            }
        }
        return $this;
    }

    //查询
    function select()
    {
        $selectSql = " select $this->field from $this->tableName $this->where $this->order $this->limit ";
        //var_dump($selectSql);
        $sth = self::$cn->prepare("$selectSql");
        for ($i = 0; $i < $this->whereValuesCount; $i++) {
            $sth->bindValue($i + 1, $this->whereValues[$i], PDO::PARAM_STR);
        }
        $sth->execute();
        return $sth;
    }

    //增加
    function add()
    {
        $addSql = " insert into $this->tableName ($this->field) values ($this->values) ";
        //var_dump($addSql);
        $sth = self::$cn->prepare("$addSql");
        if (!is_array($this->first)) {
            //插入一行
            for ($i = 0; $i < $this->countOut; $i++) {
                $sth->bindValue($i + 1, $this->valuesValues[$i], PDO::PARAM_STR);
            }
        } else {
            //插入多行
            for ($i = 0; $i < $this->countOut; $i++) {
                for ($j = 0; $j < $this->countIn; $j++) {
                    self::$num++;
                    $sth->bindValue(self::$num, $this->valuesValues[$i][$j], PDO::PARAM_STR);
                    //echo self::$num;
                }
            }
        }
        $sth->execute();
        return $sth;
    }

    //删除(delete函数同名)
    function deletes()
    {
        $deleteSql = " delete from $this->tableName $this->where ";
        $sth       = self::$cn->prepare("$deleteSql");
        for ($i = 0; $i < $this->whereValuesCount; $i++) {
            $sth->bindValue($i + 1, $this->whereValues[$i], PDO::PARAM_STR);
        }
        $sth->execute();
        return $sth;
    }

    //更新
    function update()
    {
        $updateSql = " update $this->tableName set $this->fieldValues $this->where ";
        //var_dump($updateSql);
        $sth = self::$cn->prepare("$updateSql");
        for ($i = 0; $i < $this->upCount; $i++) {
            $sth->bindValue($i + 1, $this->upValues[$i], PDO::PARAM_STR);
        }
        $sth->bindValue($this->upCount + 1, $this->whereValues, PDO::PARAM_STR);
        $sth->execute();
        return $sth;
    }


}