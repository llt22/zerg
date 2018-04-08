<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8
 * Time: 16:37
 */

namespace app\api\controller\v1;


use app\api\validate\controller\Count;
use app\api\model\Product as ProductModel;

class Product
{
    public function getMostRecentProducts($count=15)
    {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecentProducts($count);
        return json($products);
    }
}