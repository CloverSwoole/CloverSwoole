<?php
namespace CloverSwoole\CloverSwoole\Facade\Command\Abstracts;

/**
 * Class Command
 * @package CloverSwoole\CloverSwoole\Facade\Command\Abstracts
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
     * SwooleHttpServer constructor.
     * @param string $name
     */
    public function __construct($name = '')
    {
        if (null !== $name) {
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
     * @param \CloverSwoole\CloverSwoole\Command $command
     */
    public function load(\CloverSwoole\CloverSwoole\Command $command)
    {
        $command -> addCommand($this -> name,static::class);
    }

    /**
     * 运行命令
     * @param $command
     */
    public function run($command)
    {
        /**
         * 接收命令
         */
        $this -> command = $command['command'];
        /**
         * 接收参数
         */
        $this -> options = $command['options'];
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