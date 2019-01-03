<?php
namespace Itxiao6\Framework\Facade\Command;

/**
 * 命令行解析
 * Class Command
 * @package Itxiao6\Framework\Facade\Command
 */
class Command
{
    static function commandHandler()
    {
        return self::commandParser();
    }

    protected static function commandParser()
    {
        global $argv;
        $command = '';
        $options = array();
        if (isset($argv[1])) {
            $command = $argv[1];
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
}