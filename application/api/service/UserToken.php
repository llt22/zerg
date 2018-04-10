<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8
 * Time: 19:59
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use think\Exception;
use app\lib\exception\WeChatException;
use app\api\model\User as UserModel;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(
            config('wx.login_url'),
            $this->wxAppID,
            $this->wxAppSecret,
            $this->code);
    }

    public function getParams(){
        return [
            'code'=>$this->code,
            'wxAppID'=>$this->wxAppID,
            'wxAppSecret'=>$this->wxAppSecret,
            'wxLoginUrl'=>$this->wxLoginUrl,
        ];
    }

    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        } else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                $this->processLoginError($wxResult);
            } else {
               return $this->grantToken($wxResult);
            }
        }
    }

    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'error_code' => $wxResult['errcode']
        ]);
    }

    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user;
    }

    private function prepareCachedValue($wxResult, $uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        // scope=16 代表app用户的权限数值
        $cachedValue['scope'] = ScopeEnum::User;
        return $cachedValue;
    }

    private function saveToCache($cachedValue)
    {
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_in');
        $result = cache($key, $value, $expire_in);
        if (!$result) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'error_code' => 10005
            ]);
        }
        return $key;
    }

    private function grantToken($wxResult)
    {
        /*
         * 拿到openID
         * 从数据库查看是否已经存在
         * 如果存在，不处理，不存在新增一条user记录
         * 生成令牌，准备缓存数据，写入缓存
         * 把令牌返回给客户端
         * */
        $openID = $wxResult['openid'];
        $user = UserModel::getByOpenID($openID);
        if ($user) {
            $uid = $user->id;
        } else {
            $user = $this->newUser($openID);
            $uid = $user->id;
        }
        $cachedValue = $this->prepareCachedValue($wxResult, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;

    }


}