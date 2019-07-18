<?php
namespace CloverSwoole\Command\Abstracts;

/**
 * Class Command
 * @package CloverSwoole\Command\Abstracts
 */
abstract class Command
{
    /**
     * 命令名称
     * @var string
     */
    protected $name = '';
    /**
     * 命令描述
     * @var string
     */
    protected $description = '';
    /**
     * 操作命令
     * @var string
     */
    protected $command = [];
    /**
     * 附带参数
     * @var array
     */
    protected $options = [];


    /**
     * 设置命令名称
     * @param $name
     * @return $this
     */
    protected function setName($name)
    {
        $this -> name = $name;
        return $this;
    }

    /**
     * 实例化命令
     * @param string $name
     * @return Command
     */
    public static function getInterface($name = '')
    {
        return (new static(...func_get_args()));
    }

    /**
     * 实例化命令
     * SwooleHttpServer constructor.
     * @param string $name
     */
    public function __construct($name = '')
    {
        if (strlen($name) > 0) {
            $this->setName($name);
        }else{
            $this -> setName(static::class);
        }
        $this->configure();
    }
    /**
     * 设置命令描述
     * @param string $description
     * @return $this
     */
    public function setDescription($description = '')
    {
        $this -> description = $description;
        return $this;
    }

    /**
     * 命令配置
     */
    protected function configure()
    {

    }


    /**
     * 载入命令
     * @param \CloverSwoole\Command $command
     */
    public function load(\CloverSwoole\Command $command)
    {
        $command -> addCommand($this -> name,static::class);
    }

    /**
     * 默认的帮助方法
     */
    protected function help(){}

    /**
     * 运行命令
     * @param $command
     */
    public function run($command)
    {
        /**
         * 接收命令
         */
        $this -> command = isset($command['command'])&&count($command['command'])?$command['command']:'help';
        /**
         * 接收参数
         */
        $this -> options = isset($command['options'])&&count($command['options'])>0?$command['options']:[];
        /**
         * 执行命令
         */
        $this -> execute();
    }

    /**
     * 执行
     * @return mixed
     */
    abstract protected function execute();
}