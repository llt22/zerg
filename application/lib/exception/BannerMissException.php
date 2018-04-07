<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/6
 * Time: 17:25
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $msg = '你访问的banner类型不存在';
}