<?php
namespace CloverSwoole\Databases;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Fluent;

/**
 * 数据库链接配置
 * Class DbConfig
 * @package CloverSwoole\Databases
 */
class DbConfig
{
    /**
     * 链接状态
     * @var bool
     */
    public static $connection_status = false;
    /**
     * 实例
     * @var null
     */
    protected static $inetrface = null;
    /**
     * 数据库配置
     * @var array
     */
    protected $conifg = [];
    /**
     * 获取配置接口
     * @return DbConfig|null
     */
    public static function getInterface()
    {
        if(self::$inetrface == null){
            return new static();
        }
        return self::$inetrface;
    }

    /**
     * 检查数据库链接
     */
    public static function checkDatabsesConnection()
    {
        /**
         * 判断数据库是否已经连接
         */
        if (!self::$connection_status) {
            /**
             * 实例化管理器
             */
            $capsule = new Manager(DbConfig::getInterface() -> getContainer());
            /**
             * 设置全局静态访问
             */
            $capsule->setAsGlobal();
            /**
             * 启动组件
             */
            $capsule->bootEloquent();
            /**
             * 修改状态
             */
            self::$connection_status = true;
        }else{
            try{
                @Manager::connection() -> getPdo()->getAttribute(\PDO::ATTR_SERVER_INFO);
            }catch (\PDOException $throwable){
                self::reconnection($throwable);
            }
            catch (\Throwable $throwable){
                self::reconnection($throwable);
            }
        }
    }

    /**
     * @param \Throwable $throwable
     * @throws \Throwable
     */
    public static function reconnection(\Throwable $throwable)
    {
        if(in_array($throwable -> getCode(),['2002','HY000'])){
            self::$connection_status = false;
            self::checkDatabsesConnection();
        }else{
            throw $throwable;
        }
    }
    /**
     * 设置全局访问
     * @return $this
     */
    public function setGlobal()
    {
        self::$inetrface = $this;
        return $this;
    }
    /**
     * 获取配置容器
     * @return Container
     */
    public function getContainer()
    {
        /**
         * 实例化容器
         */
        $container = new Container();
        /**
         * 检查配置容器
         */
        if (! $container->bound('config')) {
            $container->instance('config', new Fluent);
        }
        /**
         * 获取连接
         */
        $connection = $container['config']['database.connections'];
        /**
         * 循环添加连接
         */
        foreach ($this -> conifg as $name=>$config){
            $connection[$name] = $config;
        }
        /**
         * 从新赋值
         */
        $container['config']['database.connections'] = $connection;
        /**
         * 返回容器
         */
        return $container;
    }
    /**
     * 添加一个连接
     * @param $config
     * @param string $name
     * @return $this
     */
    public function addConnectionItem($config,$name = 'default')
    {
        $this -> conifg[$name] = $config;
        return $this;
    }
    /**
     * 获取连接
     * @param string $name
     * @return mixed|null
     */
    public function getConnectionItem($name = 'default')
    {
        return isset($this -> conifg[$name])?$this -> conifg[$name]:null;
    }
}