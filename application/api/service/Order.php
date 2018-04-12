<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11
 * Time: 18:48
 */

namespace app\api\service;


use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;

class Order
{
    // 客户端传来的products参数
    protected $oProducts;
    // 从数据库里面查询的的数据
    protected $products;

    protected $uid;

    // 下单
    public function place($uid, $oProducts)
    {
        // oProducts 和 products 作对比
        // products 从数据库中查询出来
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if (!$status) {
            $status['order_id'] = -1;
            return $status;
        }

        // 开始创建订单
        $orderSnap = $this->snapOrder();

    }

    // 生成订单快照
    private function snapOrder($status)
    {

        $snap = [
            'orderPrice' => 0,
            'totalCount' => true,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName'
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] += $status['totalCount'];
        $snap['pStatus'] += $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if (count($this->products) > 1) {
            $snap['snapName'] .= '等';
        }

    }

    private function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id', '=', $this->uid)->find();
        if (!$userAddress) {
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败'
            ]);
        }

        return $userAddress->toArray();

    }


    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];
        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);
            if (!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['totalCount'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;

    }

    private function getProductStatus($oPID, $oCount, $products)
    {
        $pIndex = 1;
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0
        ];
        for ($i = 0; $i < count($products); $i++) {
            if ($oPID == $products[$i]['id']) {
                $pIndex = $i;
            }
        }
        if ($pIndex == -1) {
            throw new OrderException([
                'msg' => '商品不存在'
            ]);
        } else {
            $product = $products[$pIndex];
            if ($product['stock'] - $oCount >= 0) {
                $pStatus['haveStock'] = true;
            }
            $pStatus = [
                'id' => $pIndex,
                'haveStock' => false,
                'count' => $oCount,
                'name' => $product['name'],
                'totalPrice' => $product * $oCount,
            ];
        }

        return $pStatus;
    }


    // 根据订单信息查找真实的商品信息
    private function getProductsByOrder($oProducts)
    {
//        foreach ($oProducts as $oProduct) {
//            // 循环查询数据库，应该避免
//        }

        $oPIDS = [];
        foreach ($oProducts as $oProduct) {
            array_push($oPIDS, $oProduct['product_id']);
        }
        $products = Product::all($oPIDS)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();
        return $products;
    }
}