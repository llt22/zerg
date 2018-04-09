<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9
 * Time: 13:39
 */

namespace app\api\service;


class Token
{
    public static function generateToken()
    {
        $randChars = getRandChar(32);
        // 用三组字符串，进行md5加密
        $timeStamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // salt
        $salt = config('secure.token_salt');
        return md5($randChars.$timeStamp.$salt);
    }
}