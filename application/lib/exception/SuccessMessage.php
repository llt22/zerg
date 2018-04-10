<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 14:49
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
// HTTP 状态码
    public $code = 201;
    // 错误具体信息
    public $msg = 'ok';
    // 自定义的错误码
    public $error_code = 0;
}