<?php
namespace CloverSwoole\Route;

/**
 * 路由配置
 * Class RouteConfig
 * @package CloverSwoole\Route
 */
class RouteConfig
{
    /**
     * @var null | static
     */
    protected static $interface = null;
    /**
     * 配置内容
     * @var array
     */
    protected $config = [
        // 控制器的记录路径
        'controllerNameSpace'=>'\\App\\Http\\Controller\\',
        // 默认控制器名称
        'defaultControllerName'=>'Index',
        // 默认操作名称
        'defaultActionName'=>'index',
        // 进程控制器数量
        'maxPoolNum'=>15,
        // 最大深度
        'maxDepth'=>5,
    ];

    /**
     * 修改配置
     * @param $name
     * @param $value
     * @return $this
     */
    public function setConfig($name,$value)
    {
        $this -> config[$name] = $value;
        return $this;
    }
    /**
     * 获取实例
     * @return static|null
     */
    public static function getInterface()
    {
        if(self::$interface == null){
            return new static();
        }
        return self::$interface;
    }

    /**
     * 获取配置
     * @param $name
     * @return mixed|null
     */
    public function getConfig($name)
    {
        return isset($this -> config[$name])?$this -> config[$name]:null;
    }

    /**
     * 设置全局可访问
     * @return $this
     */
    public function setGlobal()
    {
        self::$interface = $this;
        return $this;
    }
}