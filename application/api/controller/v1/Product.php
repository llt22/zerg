<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8
 * Time: 16:37
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePositiveInt;

class Product
{
    public function getMostRecentProducts($count = 15)
    {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecentProducts($count);
//        // 临时隐藏字段
//        $collection = collection($products);
//        $products = $collection->hidden(['summary']);
        $products = $products->hidden(['summary']);
        return $products;
    }
    public function getProductsFromOneCategory($id){
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsFromOneCategory($id);
        return $products;
    }

    public function getOneProduct($id){
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getOneProduct($id);
        return $product;
    }
}