<?php
namespace CloverSwoole\Swoole;
use Swoole\Server;

/**
 * Class SwooleServerConfig
 * @package CloverSwoole\Swoole
 */
class SwooleServerConfig
{
    /**
     * 注入实例
     * @var null | static
     */
    protected static $interface = null;
    /**
     * Server Config
     * @var array
     */
    protected $config = [
        'serverName'=>'CloverSwoole',
        'host'=>'0.0.0.0',
        'port'=>9501,
        'server'=>[
            'worker_num' => 8,
            'task_worker_num' => 8,
            'reload_async' => true,
            'task_enable_coroutine' => true,
            'max_wait_time'=>3
        ]
    ];

    /**
     * 设置ServerName
     * @param string $name
     * @return $this
     */
    public function setServerName(string $name)
    {
        if(strlen($name) > 0){
            $this -> config['serverName'] = $name;
        }
        return $this;
    }

    /**
     * 获取WorkerNum
     * @return mixed|null
     */
    public function getWorkerNum()
    {
        return $this -> config['worker_num']>0?$this -> config['worker_num']:null;
    }

    /**
     * 获取Server名称
     * @return mixed
     */
    public function getServerName()
    {
        return $this -> config['serverName'];
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
     * 设置全局可访问
     * @return $this
     */
    public function setGlobal()
    {
        self::$interface = $this;
        return $this;
    }

    /**
     * 设置
     * @param $name
     * @param $value
     * @return $this
     */
    public function setServer($name,$value)
    {
        $this -> config['server'][$name] = $value;
        return $this;
    }

    /**
     * 设置监听的主机名
     * @param $host
     * @return $this
     */
    public function setHost($host)
    {
        $this -> config['host'] = $host;
        return $this;
    }

    /**
     * 设置监听的端口
     * @param $port
     * @return $this
     */
    public function setPort($port)
    {
        $this -> config['port'] = $port;
        return $this;
    }

    /**
     * 获取配置
     * @return array
     */
    public function getConfig()
    {
        return $this -> config;
    }
}