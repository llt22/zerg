<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9
 * Time: 13:39
 */

namespace app\api\service;


use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken()
    {
        $randChars = getRandChar(32);
        // 用三组字符串，进行md5加密
        $timeStamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // salt
        $salt = config('secure.token_salt');
        return md5($randChars . $timeStamp . $salt);
    }

    private static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            throw new TokenException();
        } else {
            // redis返回的直接是数组，不用再转
            if (!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            if (array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                throw new Exception('获取的变量不存在');
            }
        }
    }

    public static function getCurrentUid()
    {
        $uid=self::getCurrentTokenVar('uid');
        return $uid;
    }
}