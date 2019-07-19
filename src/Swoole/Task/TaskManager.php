<?php
namespace CloverSwoole\Swoole\Task;

use CloverSwoole\Swoole\ServerManager;
use CloverSwoole\Exception\ExceptionHandler;

class TaskManager
{
    public static function async($task,$finishCallback = null,$taskWorkerId = -1)
    {
        if($task instanceof \Closure){
            try{
                $task = new SuperClosure($task);
            }catch (\Throwable $throwable){
                ExceptionHandler::getInterface() ->catchTask($throwable);
                return false;
            }
        }
        return ServerManager::getInterface()->getSwooleRawServer()->task($task,$taskWorkerId,$finishCallback);
    }

    public static function processAsync($task)
    {
        return self::async($task);
    }

    public static  function sync($task,$timeout = 0.5,$taskWorkerId = -1)
    {
        if($task instanceof \Closure){
            try{
                $task = new SuperClosure($task);
            }catch (\Throwable $throwable){
                ExceptionHandler::getInterface() ->catchTask($throwable);
                return false;
            }
        }
        return ServerManager::getInterface()->getSwooleRawServer()->taskwait($task,$timeout,$taskWorkerId);
    }

    public static function barrier(array $taskList,$timeout = 0.5):array
    {
        return self::taskCo($taskList,$timeout);
    }

    public static function taskCo(array $taskList,$timeout = 0.5):array
    {
        $taskMap = [];
        $finalTask = [];
        foreach ($taskList as $key => $item){
            if($item instanceof \Closure){
                try{
                    $temp = new SuperClosure($item);
                    $taskList[$key] = $temp;
                }catch (\Throwable $throwable){
                    unset($taskList[$key]);
                    ExceptionHandler::getInterface() ->catchTask($throwable);

                }
            }
            if(isset($taskList[$key])){
                $finalTask[] = $taskList[$key];
                $taskMap[count($finalTask) - 1] = $key;
            }
        }
        $result = [];
        $ret = ServerManager::getInterface()->getSwooleRawServer()->taskCo($taskList,$timeout);
        if(is_array($ret)){
            foreach ($ret as $index => $temp){
                $result[$taskMap[$index]] = $temp;
            }
        }
        return $result;
    }
}