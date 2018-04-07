<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/6
 * Time: 17:21
 */

namespace app\lib\exception;

use think\Exception;

class BaseException extends Exception
{
    // HTTP 状态码
    public $code = 400;
    // 错误具体信息
    public $msg = '参数错误';
    // 自定义的错误码
    public $error_code = 100000;

    public function __construct($params = null)
    {

        if (!is_array($params)) {
            return;
            // throw new Exception('构造函数参数必须是数组');
        }
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('msg', $params)) {
            $this->msg = $params['msg'];
        }
        if (array_key_exists('error_code', $params)) {
            $this->error_code = $params['error_code'];
        }
    }
}