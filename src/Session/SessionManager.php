<?php

namespace CloverSwoole\Session;

use CloverSwoole\Http\Request;
use CloverSwoole\Http\Response;
use CloverSwoole\Utility\Random;
use Illuminate\Support\Collection;

/**
 * Session 管理器
 * Class SessionManager
 * @package CloverSwoole\Session
 * @mixin Collection
 */
class SessionManager
{
    /**
     * Session配置
     * @var array|SessionConfig|null
     */
    protected $config = [];
    /**
     * session id
     * @var string
     */
    protected $session_id = null;
    /**
     * 数据管理器
     * @var null | Collection
     */
    protected $dataManager = null;
    /**
     * 最后一次写入的数据
     * @var string
     */
    protected $lastWriteData = '';
    /**
     * 存储器
     * @var null|StorageInterface
     */
    protected $storage = null;

    /**
     * 获取session 实例
     * @param $session_id
     * @return SessionManager
     * @throws \Exception
     */
    public static function getInterface($session_id)
    {
        return new static($session_id);
    }

    /**
     * 构造器
     * SessionManager constructor.
     * @param null $session_id
     * @throws \Exception
     */
    protected function __construct($session_id = null)
    {
        /**
         * 获取配置
         */
        $this->config = SessionConfig::getInterface();
        /**
         * 处理session id
         */
        $this->make_session_id($session_id);
        /**
         * 初始化存储驱动
         */
        $this->initStorage();
        /**
         * 初始化数据
         */
        $this->initDataManager();
    }

    /**
     * 启动session
     * @return $this
     */
    public function start()
    {
        /**
         * 判断Cookie 是否有变动
         */
        if ($this->config->getConfig('setCookie') && Request::getInterface()->getCookie($this->config->getConfig('cookieName')) != $this->session_id) {
            /**
             * 设置Session id
             */
            Response::getInterface()->withCookie($this->config->getConfig('cookieName'), $this->session_id);
        }
        return $this;
    }

    /**
     * 初始化存储介质
     * @throws \Exception
     */
    protected function initStorage()
    {
        /**
         * 获取存储介质的配置
         */
        $class = $this->config->getConfig('storageDriver');
        /**
         * 判断类名是否存在
         */
        if (!class_exists($class)) {
            throw new \Exception('Session 存储介质不存在');
        }
        /**
         * 实例化存在介质
         */
        $this->storage = new $class($this->config->getConfig('storageConfig'));
        /**
         * 判断是否实现了接口
         */
        if (!($this->storage instanceof StorageInterface)) {
            throw new \Exception('存储介质没有实现:\CloverSwoole\Session\StorageInterface');
        }
    }

    /**
     * 初始化数据
     */
    protected function initDataManager()
    {
        $content = $this->storage->rand(strval($this->session_id));
        $this->dataManager = Collection::make(json_decode(strlen($content) > 0 ? $content : '{}', true));
        $this->lastWriteData = $this->dataManager->toJson();
    }

    /**
     * 销毁会话
     */
    protected function destroy()
    {
        /**
         * 清空数据
         */
        $this->dataManager = Collection::make();
        /**
         * 删除会话
         */
        return $this->storage->delete($this->session_id);
    }

    /**
     * 保存数据
     */
    public function save()
    {
        /**
         * 判断是否存在修改
         */
        if ($this->lastWriteData != $this->dataManager->toJson()) {
            $this->storage->write(strval($this->session_id), $this->dataManager->toJson());
        }
    }

    /**
     * 设置值
     * @param $key
     * @param $value
     * @return static
     */
    public function set($key, $value)
    {
        $this->dataManager->put(...func_get_args());
        return $this;
    }

    /**
     * 获取值
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->dataManager->get(...func_get_args());
    }

    /**
     * 设置或创建session id
     * @param $session_id
     */
    protected function make_session_id($session_id = null)
    {
        /**
         * 判断session id 是否合法
         */
        if (strlen($session_id) < $this->config->getConfig('sessionLength')) {
            /**
             * 处理Cookie
             */
            if (strlen(Request::getInterface()->getCookie($this->config->getConfig('cookieName'))) >= $this->config->getConfig('sessionLength')) {
                $session_id = Request::getInterface()->getCookie($this->config->getConfig('cookieName'));
            } else {
                /**
                 * 生成session id
                 */
                $session_id = Random::randStr($this->config->getConfig('sessionLength'));
            }
        }
        /**
         * 获取 创建 session id
         */
        $this->session_id = $session_id;
    }

    /**
     * 装饰者
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->dataManager->{$name}(...func_get_args());
    }

    /**
     * 析构方法
     */
    public function __destruct()
    {
        /**
         * 保存数据
         */
        $this->save();
    }

}