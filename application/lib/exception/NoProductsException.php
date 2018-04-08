<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8
 * Time: 16:43
 */

namespace app\lib\exception;


class NoProductsException extends BaseException
{
// HTTP 状态码
    public $code = 404;
    // 错误具体信息
    public $msg = '没有对应的商品数据';
    // 自定义的错误码
    public $error_code = 100000;
}