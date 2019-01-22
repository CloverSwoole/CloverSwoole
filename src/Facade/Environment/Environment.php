<?php
namespace CloverSwoole\CloverSwoole\Facade\Environment;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

/**
 * 环境自适应
 * Class Environment
 * @package CloverSwoole\CloverSwoole\Facade\Environment
 */
class Environment implements EnvironmentInsterface
{
    /**
     * @param Container|null $container
     * @param string|null $base_dir
     * @return mixed|void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function boot(?Container $container = null,$base_dir = null)
    {
        if(!($container instanceof Container)){
            $container = new Container();
        }
        if(!is_dir($base_dir)){
            return ;
        }
        $fileSystem = new Filesystem();
        $base_dir = rtrim(rtrim($base_dir,'/'),'\\').DIRECTORY_SEPARATOR;
        $environment = '';
        $environmentPath =  $base_dir. '.env';
        if ($fileSystem->isFile($environmentPath)) {
            $environment = trim($fileSystem->get($environmentPath));
            $envFile = $base_dir . '.' . $environment;
            if ($fileSystem->isFile($envFile . '.env')) {
                $dotEnv = new \Dotenv\Dotenv($base_dir, '.' . $environment . '.env');
                $dotEnv->load();
            }
        }
    }
}