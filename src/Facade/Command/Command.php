<?php
namespace CloverSwoole\CloverSwoole\Facade\Command;

/**
 * 命令行解析
 * Class Command
 * @package CloverSwoole\CloverSwoole\Facade\Command
 */
class Command
{
//    static function commandHandler()
//    {
//        return self::commandParser();
//    }
//
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
        return array($command, $options);
    }

    protected static function loading()
    {
        self::commandParser();
    }
}