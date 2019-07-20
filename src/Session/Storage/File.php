<?php

namespace CloverSwoole\Session\Storage;

use CloverSwoole\Session\StorageInterface;

/**
 * Class File
 * @package CloverSwoole\Session\Storage
 */
class File implements StorageInterface
{
    /**
     * FileStorage 配置
     * @var array
     */
    protected $config = [
        'dir' => null,
    ];

    /**
     * 构造器
     * File constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        $this->config = array_merge($this->config, $config);
        /**
         * 处理路径结尾
         */
        $this->config['dir'] = rtrim($this->config['dir'], DIRECTORY_SEPARATOR);
        /**
         * 判断文件夹是否可写
         */
        if (!(is_dir($this->config['dir']) && is_writable($this->config['dir']))) {
            throw new \Exception('Session 存放文件夹不存在');
        }
    }

    /**
     * 写入session内容
     * @param string $session_id
     * @param string $data
     * @return bool
     */
    public function write(string $session_id, string $data): bool
    {
        return boolval(file_put_contents($this->config['dir'] . DIRECTORY_SEPARATOR . $session_id, $data));
    }

    /**
     * 读取session 内容
     * @param string $session_id
     * @return string
     */
    public function rand(string $session_id): string
    {
        return strval(file_exists($this->config['dir'] . DIRECTORY_SEPARATOR . $session_id) ? file_get_contents($this->config['dir'] . DIRECTORY_SEPARATOR . $session_id) : '');
    }

    /**
     * 删除会话
     * @param string $session_id
     * @return bool
     */
    public function delete(string $session_id): bool
    {
        return boolval(unlink($this->config['dir'] . DIRECTORY_SEPARATOR . $session_id));
    }
}