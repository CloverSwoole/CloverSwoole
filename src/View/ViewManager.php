<?php

namespace CloverSwoole\View;


/**
 * Class ViewManager
 * @package CloverSwoole\View
 */
class ViewManager
{
    /**
     * Blade 实例
     * @var null|Blade
     */
    protected $blade = null;
    /**
     * 配置实例
     * @var null | ViewConfig
     */
    protected $config = null;

    /**
     * 新增模板路径
     * @return $this
     */
    public function addLocation()
    {
        $this->blade->view()->addLocation();
        return $this;
    }

    /**
     * 新增拓展名
     * @return $this
     */
    public function addExtension()
    {
        $this->blade->view()->addExtension();
        return $this;
    }

    /**
     * 渲染模板
     * @param string $view
     * @param array $data
     * @return string
     */
    public function reader(string $view, $data = [])
    {
        /**
         * 创建view
         */
        $view = $this->blade->view()->make($view, $data);
        /**
         * 渲染View
         */
        return $view->render();
    }

    /**
     * 构造器
     * ViewManager constructor.
     */
    public function __construct()
    {
        /**
         * 合并配置
         */
        $this->config = ViewConfig::getInterface();
        /**
         * 实例化Blade
         */
        $this->blade = new Blade($this->config->getViewPath(), $this->config->getCachePath());
    }
}