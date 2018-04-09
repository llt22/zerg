<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/7
 * Time: 18:42
 */

namespace app\api\model;

use app\lib\exception\NoProductsException;

class Product extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time', 'create_time', 'pivot', 'from'];

    public function getMainImgUrlAttr($value, $data)
    {
        return $this->addPrefixForImage($value, $data);
    }

    public static function getMostRecentProducts($count)
    {
        $products = self::limit($count)->order('create_time desc')->select();
        if ($products->isEmpty()) {
            throw new NoProductsException();
        }
        return $products;
    }

    public static function getProductsFromOneCategory($id)
    {
//        $products = Category::with('products')->find($id);

        $products = self::where('category_id', '=', $id)->select();
        if ($products->isEmpty()) {
            throw new NoProductsException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    public static function getOneProduct($id)
    {
        $product = self::with('productImages,productImages.image,properties')->find($id);
        if (!$product) {
            throw new NoProductsException();
        }
        $products = $product->hidden(['summary']);
        return $product;
    }

    public function productImages()
    {
        return $this->hasMany('productImage', 'product_id', 'id');
    }

    public function properties()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }
}