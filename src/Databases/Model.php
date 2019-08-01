<?php
namespace CloverSwoole\Databases;
use Illuminate\Database\Query\Builder;

/**
 * Class Model
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @package CloverSwoole\Databases
 * @mixin Builder
 */
class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * 重写构造器
     * Model constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        DbConfig::getInterface() -> checkDatabsesConnection();
        parent::__construct($attributes);
    }

    /**
     * 重写魔术方法
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws \Throwable
     */
    public static function __callStatic($method, $parameters)
    {
        try{
            return parent::__callStatic($method, $parameters);
        }catch (\Throwable $throwable){
            DbConfig::reconnection($throwable);
        }
    }
}