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
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserNotExistException;

class Address
{
    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();
        // 根据Token来获取uid
        // 根据uid 来查找用户数据，判断用户是否存在，若果不存在抛出异常
        // 获取用户提交的地址信息
        // 根据地址信息是否存在，来判断是添加还是更新
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserNotExistException(['msg' => '此用户不存在']);
        }
        $dataArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if (!$userAddress) {
            $user->address()->save($dataArray);
        } else {
            // 更新数据
            $user->address->save($dataArray);
        }
        // rest 要求修改或者添加数据成功后返回新的数据
        // 我们使用自定义的exception返回成功数据
        return json(new SuccessMessage(),201);
    }
}