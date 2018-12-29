<?php
include './vendor/autoload.php';

use Itxiao6\Framework\Kernel;

/**
 *
 */
use Illuminate\Config\Repository;




$app = Kernel::getInterface();

$app -> bind('database',\Illuminate\Database\Capsule\Manager::class);
$app -> bind('file_system',\Illuminate\Filesystem\Filesystem::class);

//$app -> bind('config',\Illuminate\Support\Fluent::class);
//dump($app);
//return ;
$app -> instance('config',new \Illuminate\Config\Repository());;
//dump($config);
//return ;
//dump($app -> make('config'));
//return ;
//dump($app -> make('file_system') -> get('index.php'));
$app -> make('database',[$app]);
//dump();

