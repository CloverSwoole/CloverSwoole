<?php
namespace App\Http\Controller;
use App\Models\Users;
use CloverSwoole\CloverSwoole\Facade\Hook\Hook;
use CloverSwoole\CloverSwoole\Facade\Http\Abstracts\Controller;
use CloverSwoole\CloverSwoole\Facade\Http\Request;
use CloverSwoole\CloverSwoole\Facade\Http\Response;
use CloverSwoole\CloverSwoole\Facade\SwooleHttp\ServerManage;
/**
 *
 * Class Index
 * @package App\Http\Controller
 */
class Index extends Controller
{
    /**
     * JSON 数据返回
     */
    function index()
    {
        $phone = '1233123';
        $code = '12342';
        // 协程任务
        Hook::getInterface() -> add_coroutine('response_end',function()use($code,$phone){
            \co::sleep(5);
            echo "发送成功,手机号:{$phone},code:{$code}\n";
        });
        $mail = 'itxiao6@qq.com';
        // 普通任务
        Hook::getInterface() -> add_ordinary('response_end',function()use($code,$mail){
            \co::sleep(5);
            echo "发送成功,邮箱:{$mail},code:{$code}\n";
        });
        // 异步任务
        Hook::getInterface() -> add_async('response_end',function()use($code,$mail){
            /**
             * 建议内部使用协程
             */
            \go(function()use($mail,$code){
                \co::sleep(5);
                echo "发送成功,:邮箱:{$mail},code:{$code}\n";
            });
        });
        $this -> returnJosn(['name'=>'戒尺','age'=>20,'sex'=>'男']);
    }

    /**
     * 打印数据
     */
    function dump_test()
    {
        Response::dump(Request::getInterface() -> getGetParam() -> getGets());
    }

    /**
     * 测试模拟登陆接口
     */
    function test_login()
    {
        try{
            Users::login($this -> __getRequest() -> getPostParam() -> getPost('username'),$this -> __getRequest() -> getPostParam() -> getPost('password'));
            $this -> returnJosn(['status'=>200,'msg'=>'登录成功']);
        }catch (\Throwable $throwable){
            $this -> returnJosn(['status'=>400,'msg'=>$throwable -> getMessage()]);
        }
    }

    /**
     * 容器相关操作
     */
    public function container_test()
    {
        Response::dump(\CloverSwoole\CloverSwoole\Framework::getContainerInterface());
    }

    /**
     * db 测试
     */
    public function db_test()
    {
        $this -> returnJosn(Users::where('id','!=',1) -> get());
    }

    /**
     * 服务信息 操作
     */
    public function server_debug()
    {
        Response::dump(ServerManage::getInterface() -> getRawServer());
    }

    /**
     * 获取路由相关信息
     */
    function route()
    {
        $url = $this -> route -> getRequest() -> getRawRequest() -> server['path_info'];
        Response::dump($url);
    }
}