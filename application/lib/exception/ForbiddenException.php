<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11
 * Time: 1:59
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    // HTTP 状态码
    public $code = 403;
    // 错误具体信息
    public $msg = '权限不够';
    // 自定义的错误码
    public $error_code = 10001;
}