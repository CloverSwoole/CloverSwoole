<?php

namespace CloverSwoole\Session\Storage;

use CloverSwoole\Session\StorageInterface;

/**
 * Class Redis
 * @package CloverSwoole\Session\Storage
 */
class Redis implements StorageInterface
{
    /**
     * Redis 配置
     * @var array
     */
    protected $config = [
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => null,
        'prefix' => 'clover_swoole:'
    ];
    /**
     * Redis 连接
     * @var null | \Redis
     */
    protected $redis = null;

    /**
     * 构造器
     * Redis constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        /**
         * 判断当前环境是否支持Redis
         */
        if (!class_exists(\Redis::class)) {
            throw new \Exception('请先安装PHPRedis扩展');
        }
        /**
         * 处理配置
         */
        $this->config = array_merge($this->config, $config);
        /**
         * 实例化Redis实例
         */
        $this->redis = new \Redis();
        /**
         * 建立连接
         */
        $this->redis->connect($this->config['host'], $this->config['port']);
        /**
         * 判断连接是否需要密码
         */
        if (strlen($this->config['password']) > 0) {
            $this->redis->auth($this->redis['password']);
        }
    }

    /**
     * 写入数据
     * @param string $session_id
     * @param string $data
     * @return bool
     */
    public function write(string $session_id, string $data): bool
    {
        return boolval($this->redis->set(rtrim($this->config['prefix'], ':') . ':' . $session_id, $data));
    }

    /**
     * 读取session内容
     * @param string $session_id
     * @return string
     */
    public function rand(string $session_id): string
    {
        return strval($this->redis->get(rtrim($this->config['prefix'], ':') . ':' . $session_id));
    }

    /**
     * 删除会话
     * @param string $session_id
     * @return bool
     */
    public function delete(string $session_id): bool
    {
        return intval($this->redis->del(rtrim($this->config['prefix'], ':') . ':' . $session_id));
    }
}