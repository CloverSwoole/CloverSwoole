<?php

namespace CloverSwoole\Session;

/**
 * Session 存储器的接口规范
 * Interface StorageInterface
 * @package CloverSwoole\Session
 */
interface StorageInterface
{
    /**
     * 构造器
     * StorageInterface constructor.
     * @param array $config
     */
    public function __construct(array $config);

    /**
     * 获取数据
     * @param $session_id
     * @return string
     */
    public function rand(string $session_id): string;

    /**
     * 写入数据
     * @param string $session_id
     * @param string $data
     * @return bool
     */
    public function write(string $session_id, string $data): bool;

    /**
     * 删除会话数据
     * @param string $session_id
     * @return bool
     */
    public function delete(string $session_id): bool;
}