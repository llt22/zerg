<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8
 * Time: 17:59
 */

namespace app\api\controller\v1;
use app\api\model\Category as CategoryModel;

class Category
{
    public function getCategory(){
        $category = CategoryModel::getCategory();
        return $category;
    }
}