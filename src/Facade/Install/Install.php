<?php
namespace CloverSwoole\CloverSwoole\Facade\Install;
/**
 * 安装框架
 * Class Install
 * @package CloverSwoole\CloverSwoole\Facade\Install
 */
class Install
{
    protected $dir = '';
    public static function install()
    {
        self::make_app_dir();
        self::make_config_dir();
        self::write_composer_json();
    }

    /**
     * 创建app基础目录及演示控制器
     */
    public static function make_app_dir()
    {
        mkdir(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR);
        mkdir(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Event'.DIRECTORY_SEPARATOR);
        file_put_contents(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Event'.DIRECTORY_SEPARATOR.'SwooleHttpEvent.php',file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'SwooleHttpEvent.php'));
        file_put_contents(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Event'.DIRECTORY_SEPARATOR.'SwooleSocketEvent.php',file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'SwooleSocketEvent.php'));
        mkdir(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR);
        mkdir(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controller'.DIRECTORY_SEPARATOR);
        file_put_contents(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controller'.DIRECTORY_SEPARATOR.'Index.php',__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'Index.php');
        mkdir(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Models'.DIRECTORY_SEPARATOR);
        file_get_contents(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Models'.DIRECTORY_SEPARATOR.'Users.php',file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'Users.php'));
        mkdir(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Socket'.DIRECTORY_SEPARATOR);
        mkdir(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Socket'.DIRECTORY_SEPARATOR.'Index'.DIRECTORY_SEPARATOR);
        file_put_contents(getcwd().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Socket'.DIRECTORY_SEPARATOR.'Index'.DIRECTORY_SEPARATOR.'Index.php',file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'SocketControllerIndex.php'));
    }

    /**
     * 创建配置文件夹及配置文件
     */
    public static function make_config_dir_and_file()
    {
        mkdir(getcwd().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR);
        file_put_contents(getcwd().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Databases.php',file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'Databases.php'));
        file_put_contents(getcwd().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'SwooleHttpConfig.php',file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'SwooleHttpConfig.php'));
        file_put_contents(getcwd().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'SwooleSocketConfig.php',file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'SwooleSocketConfig.php'));

    }

    /**
     * 重写composer.json
     */
    public static function write_composer_json()
    {
        file_put_contents(getcwd().DIRECTORY_SEPARATOR.'composer.json',file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'composer.json'));
    }

    /**
     * 创建启动脚本的目录及脚本
     */
    public static function make_bin_dir_and_script()
    {
        mkdir(getcwd().DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR);
        file_put_contents(getcwd().DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'web_server.sh',file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'web_server.sh'));
        file_put_contents(getcwd().DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'web_and_socket_server.sh',file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'web_and_socket_server.sh'));
    }
}

/**
 * 引入依赖
 */
require_once(getcwd().DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
/**
 * 安装
 */
Install::install();