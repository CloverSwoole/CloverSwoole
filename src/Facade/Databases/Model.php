<?php
namespace Itxiao6\Framework\Facade\Databases;
use Illuminate\Database\Query\Builder;
use Itxiao6\Framework\Framework;

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
            Framework::getContainerInterface() -> make('db');
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