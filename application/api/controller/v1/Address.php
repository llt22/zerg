<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 13:32
 */

namespace app\api\controller\v1;


use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
class Address
{
    public function createOrUpdateAddress()
    {
        (new AddressNew())->goCheck();
        // 根据Token来获取uid
        // 根据uid 来查找用户数据，判断用户是否存在，若果不存在抛出异常
        // 获取用户提交的地址信息
        // 根据信息是否存在，来判断是添加还是更新
        return 'address';
    }
}