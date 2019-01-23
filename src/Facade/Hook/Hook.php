<?php
namespace CloverSwoole\CloverSwoole\Facade\Hook;

/**
 * Class Hook
 * @package CloverSwoole\CloverSwoole\Facade\Hook
 */
class Hook
{
    /**
     * 全局接口
     * @var array
     */
    protected static $global_insterface = [];
    /**
     * 钩子管理器名称
     * @var string
     */
    protected $hook_name = '';
    /**
     * Hook lists
     * @var array
     */
    protected $hook_lists = [];

    /**
     * Hook constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this -> hook_name = $name;
    }

    /**
     * 获取实例
     * @param null $name
     * @return mixed|static
     */
    public static function getInterface($name = null)
    {
        if(!(isset(self::$global_insterface[$name]) && self::$global_insterface[$name] instanceof Hook)){
            self::$global_insterface[$name] = new static(...func_get_args());
        }
        return self::$global_insterface[$name];
    }
    /**
     * 新增普通任务
     * @param $name
     * @param null $callback
     */
    public function add_ordinary(string $name,\Closure $callback = null)
    {
        if($callback instanceof \Closure || is_callable($callback)){
            $this -> hook_lists[$name][] = ['callback'=>$callback,'type'=>'ordinary'];
        }
    }
    /**
     * 新增异步任务
     * @param $name
     * @param null $callback
     */
    public function add_async(string $name,\Closure $callback = null)
    {
        if($callback instanceof \Closure || is_callable($callback)){
            $this -> hook_lists[$name][] = ['callback'=>$callback,'type'=>'async'];
        }
    }
    /**
     * 新增异步任务
     * @param $name
     * @param null $callback
     */
    public function add_coroutine(string $name,\Closure $callback = null)
    {
        if($callback instanceof \Closure || is_callable($callback)){
            $this -> hook_lists[$name][] = ['callback'=>$callback,'type'=>'coroutine'];
        }
    }

    /**
     * 监听hook
     * @param $name
     * @param array $param
     * @param bool $is_clear
     */
    public function listen(string $name,array $param,bool $is_clear = false)
    {
        if(isset($this -> hook_lists[$name])){
            foreach ($this -> hook_lists[$name] as $item){
                if(isset($item['type']) && $item['type'] == 'ordinary'){
                    $item['callback'](...$param);
                }else if(isset($item['type']) && $item['type'] == 'coroutine'){
                    \go(function()use($item,$param){
                        $item['callback'](...$param);
                    });
                }
            }
        }
    }
}