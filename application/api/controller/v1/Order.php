<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11
 * Time: 2:12
 */

namespace app\api\controller\v1;

use think\Request;
use app\api\service\Token;
use app\api\service\Order as OrderService;
use app\api\validate\OrderPlace;
class Order extends BaseController
{
    public function placeOrder()
    {
        /*
         * 验证用户提交下单的数据
         * 接收数据后，检查库存（因为许多人在下单）
         * 有库存，把订单数据存入数据库中。返回客户端消息，告诉客户端可以支付了
         * 调用支付接口，进行支付
         * 还需要再次进行库存检测
         * 服务器调用微信支付接口进行支付
         * 微信返回支付结果
         * 成功，再次进行库存的检查
         * 成功：进行库存量扣除，失败：返回一个支付失败的结果
         * */
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = Token::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid, $products);
        return $status;


    }

    protected $beforeActionList = [
        // 在执行 generateOrder 执行之前先执行 checkExclusiveScope
        'checkExclusiveScope' => ['only' => 'placeOrder']
    ];
}