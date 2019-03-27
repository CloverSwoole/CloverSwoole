<?php
namespace CloverSwoole\CloverSwoole\Facade\Databases;
use Illuminate\Database\Query\Builder;
use CloverSwoole\CloverSwoole\Framework;

/**
 * 基础模型
 * Class Model
 * @package App\Models
 * @mixin Builder
 */
class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * 数据库链接状态
     * @var bool
     */
    public static $db_connection_status = false;
    /**
     * 检查数据库链接
     */
    public static function __check_db_connection()
    {
        if(!self::$db_connection_status){
            if(!Framework::exists_bind(DatabaseInterface::class)){
                Framework::getContainerInterface() -> bind(DatabaseInterface::class,Database::class);
            }
            Framework::getContainerInterface() -> make(DatabaseInterface::class) -> boot(Framework::getContainerInterface());
            self::$db_connection_status = true;
        }
    }
    public static function __callStatic($method, $parameters)
    {
        // 检查数据库是否已经链接
        self::__check_db_connection();
        return parent::__callStatic($method, $parameters);
    }
    public function __call($method, $parameters)
    {
        // 检查数据库是否已经链接
        self::__check_db_connection();
        return parent::__call($method, $parameters);
    }
    public function __construct(array $attributes = [])
    {
        // 检查数据库是否已经链接
        self::__check_db_connection();
        parent::__construct($attributes);
    }
}