<?php
namespace CloverSwoole\CloverSwoole;

/**
 * Class Command
 * @package CloverSwoole\CloverSwoole
 */
class Command
{
    /**
     * 命令类列表
     * @var array
     */
    protected $class_lists = [];
    /**
     * 命令列表
     * @var array
     */
    protected $command_lists = [];
    /**
     * 新增命令
     * @param $name
     * @param $class
     * @return $this
     */
    public function addCommand($name,$class)
    {
        $this -> command_lists[$name] = ['class'=>$class];
        return $this;
    }

    /**
     * 获取接口
     * @param $class_lists
     * @return Command
     */
    public static function getInterface($class_lists)
    {
        return new static(...func_get_args());
    }
    /**
     * 命令解析
     * @return array
     */
    protected static function commandParser()
    {
        global $argv;
        $command = [];
        $options = array();
        foreach ($argv as $key=>$item){
            if($key == 0){continue;}
            if ((!strstr($item,'--')) && (!strstr($item,'-'))) {
                $command[] = $item;
            }else{
                break;
            }
        }
        foreach ($argv as $item) {
            if (substr($item, 0, 2) === '--') {
                $temp = trim($item, "--");
                $temp = explode("-", $temp);
                $key = array_shift($temp);
                $options[$key] = array_shift($temp) ?: '';
            }
        }
        return array('command'=>$command, 'options'=>$options);
    }

    /**
     * 构造器
     * Command constructor.
     * @param array $class_lists
     */
    protected function __construct(array $class_lists = [])
    {
        /**
         * 合并命令类
         */
        $this -> class_lists = array_merge($this -> class_lists,$class_lists);
        /**
         * 解析命令
         */
        $command = self::commandParser();
        /**
         * 加载命令
         */
        self::load();
        /**
         * 判断是否要调用命令
         */
        if(isset($command['command'][0]) && strlen($command['command'][0]) > 0 && isset($this -> command_lists[$command['command'][0]]['class']) && class_exists($this -> command_lists[$command['command'][0]]['class'])){
            /**
             * 拼接命令类
             */
            $class = $this -> command_lists[$command['command'][0]]['class'];
            /**
             * 删除一级命令
             */
            unset($command['command'][0]);
            /**
             * 更新命令参数
             */
            $command['command'] = array_values($command['command']);
            /**
             * 运行命令
             */
            (new $class()) -> run($command);
        }
    }
    /**
     * 载入所有命令
     */
    protected function load()
    {
        if(is_array($this -> class_lists) && count($this -> class_lists) > 0){
            /**
             * 循环命令类
             */
            foreach ($this -> class_lists as $key=>$item){
                /**
                 * 加载命令
                 */
                (new $item()) -> load($this);
            }
        }
    }
}