<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/6
 * Time: 17:18
 */

namespace app\lib\exception;


use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{

    private $code;
    private $msg;
    private $error_code;

    public function render(\Exception $e)
    {
        // 如果是客户端错误
        if ($e instanceof BaseException) {
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->error_code = $e->error_code;
            // 如果是服务器发生错误
        } else {
            // 或者Config::get('app_debug')
            if (config('app_debug')) {
                return parent::render($e);
            } else {
                $this->code = 500;
                $this->msg = '服务器内部错误';
                $this->error_code = 999;
                $this->recordErrorLog($e);
            }


        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'code' => $this->code,
            'error_code' => $this->error_code,
            'request_url' => $request->url()
        ];
        // $this->code 用来设置 response 状态码
        return json($result, $this->code);

    }

    private function recordErrorLog(\Exception $e)
    {
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(), 'error');
    }
}