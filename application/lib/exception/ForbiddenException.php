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

    public $code = 403;
    public $msg = '权限不够';
    public $error_code = 10001;
}