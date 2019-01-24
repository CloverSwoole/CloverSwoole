<?php
namespace App\Models;
use CloverSwoole\CloverSwoole\Facade\Databases\Model;
/**
 * 用户模型
 * Class Users
 * @package App\Models
 */
class Users extends Model
{
    protected $table = "users";
    public static function login($username,$password)
    {
        return true;
    }
}