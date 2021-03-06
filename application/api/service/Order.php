<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11
 * Time: 18:48
 */

namespace app\api\service;

use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\NoProductsException;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;

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

        // 生成订单快照
        $orderSnap = $this->snapOrder($status);
        $this->createOrder($orderSnap);

        return json($status);
    }

    // 生成订单快照
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => true,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => null,
            'snapImg' => null,
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
        return $snap;
    }

    private function createOrder($snap)
    {
        Db::startTrans();
        try {
            // 保存订单信息
            $orderNo = $this->makeOrderNo();
            $order = new \app\api\model\Order();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();


            // 保存订单下面的商品信息
            $orderID = $order->id;
            $create_time = $order->create_time;
            foreach ($this->oProducts as &$p) {
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);

            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        } catch (Exception $ex) {
            Db::rollback();
            throw $ex;
        }

    }

    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
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
            $pStatus = $this->getProductStatus($oProduct);
            if (!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;

    }

    private function getProductStatus($oProduct)
    {
        $pID = $oProduct['product_id'];
        $oCount = $oProduct['count'];
        $product = null;
        foreach ($this->products as $p) {
            if ($p['id'] == $pID) {
                $product = $p;
            }
        }
        if (!$product) {
            throw new NoProductsException();
        }

        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0
        ];
        if ($product['stock'] - $oCount >= 0) {
            $pStatus['haveStock'] = true;
        }
        $pStatus['id'] = $product['id'];
        $pStatus['count'] = $oCount;
        $pStatus['name'] = $product['name'];
        $pStatus['totalPrice'] = $product['price'] * $oCount;
        return $pStatus;
    }


    // 根据订单信息查找真实的商品信息
    private function getProductsByOrder($oProducts)
    {
//        foreach ($oProducts as $oProduct) {
//            // 循环查询数据库，应该避免
//        }
        $IDs = [];
        foreach ($oProducts as $oProduct) {
            array_push($IDs, $oProduct['product_id']);
        }
        $products = Product::all($IDs)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();
        return $products;
    }
}