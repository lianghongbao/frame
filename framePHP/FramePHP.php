<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/23
 * Time: 10:14
 * 加载的顺序：从上到下
 */
define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']));//APP_PATH:  /mnt/testkj
define('CONTROLLER_PATH', 'application/controller/');
define('MODEL_PATH', 'application/model/');
define('VIEW_PATH', '/application/view/');
define('CONFIG', './config/');
define('PUB', './public/');

function load($class)
{
    $file = "./framePHP/" . $class . ".class.php";
    if (is_file($file)) {
        require_once("$file");
    }
}

//注册__autoload()函数
spl_autoload_register("load");

//加载公共文件，多个文件只需添加文件名
function publicLoad()
{
    $args           = func_get_args();//获取传入的参数
    $publicFileName = array_values($args[0]);
    for ($i = 0; $i < $args[1]; $i++) {
        $pubFile = PUB . "$publicFileName[$i].php";//文件路径
        if (file_exists($pubFile)) {
            require "$pubFile";
        } else {
            echo "file not exists:" . " " . "$pubFile" . "<br />";//列出不存在的文件
            break;
        }
    }
}

//文件名数组
$publicFile = [
    'function',
];
$publicNum  = count($publicFile);//统计文件数量
call_user_func_array('publicLoad', array($publicFile, $publicNum));//回调函数调用


//加载配置文件，加载完类库之后
function confLoad($confFile, $confNum)
{
    $confFileName = array_values($confFile);
    for ($j = 0; $j < $confNum; $j++) {
        $configFile = CONFIG . "$confFileName[$j].php";
        if (file_exists($configFile)) {
            require "$configFile";
        } else {
            echo "file not exists:" . " " . "$configFile" . "<br />";
            break;
        }
    }
}

$confFile = [
    'config',
];
$confNum  = count($confFile);
call_user_func_array('confLoad', array($confFile, $confNum));

$route = new Route();//实例化路由
