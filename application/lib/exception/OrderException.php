<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/12
 * Time: 18:08
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
// HTTP 状态码
    public $code = 404;
    // 错误具体信息
    public $msg = '订单不存在';
    // 自定义的错误码
    public $error_code = 80000;
}