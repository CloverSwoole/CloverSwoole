<?php
namespace CloverSwoole\Utility;

/**
 * Class Command
 * @package CloverSwoole\Utility
 */
class Command
{
    /**
     * 命令解析
     * @return array
     */
    public static function commandParser()
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
}