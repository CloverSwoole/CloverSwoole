<?php
include './vendor/autoload.php';

use Itxiao6\Framework\Kernel;

/**
 * 获取框架实例
 */
$app = Kernel::getInterface();

/**
 * 注入自定义组件
 */
$app -> bind(\Itxiao6\Framework\Facade\Databases\DatabaseInterface::class,\Itxiao6\Framework\Facade\Databases\Database::class);




/**
 * 启动框架
 */
$app -> make('framework') -> start($app);


return ;


//$app -> bind(Pay::class,WechatPay::class);
//
//$app -> make(Pay::class) -> buy();
//
//$app -> bind(Pay::class,AliPay::class);
//
//$app -> make(Pay::class) -> buy();
//
//$app -> bind(Pay::class,function(\Illuminate\Container\Container $container){
//    return new UserFunc('bank card');
//});
///**
// * 绑定自定义处理
// */
//$app -> resolving(Pay::class,function(UserFunc $userFunc){
//    $userFunc -> set_card_num('89362894639329374');
//});

//$app -> make(Pay::class) -> buy();

$app -> bind(\Itxiao6\Framework\Facade\Databases\DatabaseInterface::class,\Illuminate\Database\Capsule\Manager::class);

$app -> alias(\Itxiao6\Framework\Facade\Databases\DatabaseInterface::class,'db');

$db2= $app -> make('db');
dump($db2);

//$app -> bind('file_system',\Illuminate\Filesystem\Filesystem::class);

//$app -> bind('config',\Illuminate\Support\Fluent::class);
//dump($app);
//return ;
//$app -> instance('config',new \Illuminate\Config\Repository());;
//dump($config);
//return ;
//dump($app -> make('config'));
//return ;
//dump($app -> make('file_system') -> get('index.php'));
//$app -> make('database',[$app]);
//dump();

