<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9
 * Time: 13:19
 */

namespace app\api\model;

class User extends BaseModel
{
    public static function getByOpenID($openid)
    {
        $user = self::where('openid', '=', $openid)->find();
        return $user;
    }

    public function address(){
        // 一对一关系，从没有外键的查找有外键的用hasOne
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }
}