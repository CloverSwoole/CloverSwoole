<?php
namespace CloverSwoole\view;
/**
 * 视图配置
 * Class ViewConfig
 * @package CloverSwoole\view
 */
class ViewConfig
{
    /**
     * 配置实例
     * @var null | static
     */
    protected static $interface = null;

    /**
     * 配置内容
     * @var array
     */
    protected $config = [
        'viewsPath'=>null,
        'cachePath'=>null
    ];
    /**
     * 获取配置接口
     * @return ViewConfig|null
     */
    public static function getInterface()
    {
        if (self::$interface === null) {
            return new static();
        }
        return self::$interface;
    }
    /**
     * 设置可全局访问
     * @return $this
     */
    public function setAsGlobal()
    {
        self::$interface = $this;
        return $this;
    }
    /**
     * 设置视图文件路径
     * @param $path
     * @return $this
     */
    public function setViewPath($path)
    {
        $this -> config['viewsPath'][] = $path;
        return $this;
    }
    /**
     * 获取视图目录
     * @return mixed
     */
    public function getViewPath()
    {
        return $this -> config['viewsPath'];
    }
    /**
     * 设置缓存目录
     * @param $path
     * @return $this
     */
    public function setCachePath($path)
    {
        $this -> config['cachePath'] = $path;
        return $this;
    }
    /**
     * 获取视图目录
     * @return mixed
     */
    public function getCachePath()
    {
        return $this -> config['cachePath'];
    }
}