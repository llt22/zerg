<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//api/v1.Banner/getBanner 模块/控制器/方法
Route::rule('api/:version/banner/:id', 'api/:version.Banner/getBanner', 'GET');
Route::rule('api/:version/theme', 'api/:version.Theme/getSimpleList', 'GET');
Route::rule('api/:version/theme/:id', 'api/:version.Theme/getOneKindProducts', 'GET');
Route::rule('api/:version/products', 'api/:version.Product/getMostRecentProducts', 'GET');
Route::rule('api/:version/category', 'api/:version.Category/getCategory', 'GET');
Route::rule('api/:version/products_of_category', 'api/:version.Product/getProductsFromOneCategory', 'GET');
Route::rule('api/:version/token/user', 'api/:version.Token/getToken', 'POST');
// ['id'=>'\d+'] 只有id是数字的时候才匹配这个路由
// 路由分组
Route::group('api/:version/product', function () {
    Route::get('/:id', 'api/:version.Product/getOneProduct', [], ['id' => '\d+']);
});

Route::rule('api/:version/address', 'api/:version.Address/createOrUpdateAddress', 'POST');
Route::rule('api/:version/second', 'api/:version.Address/second', 'GET');
Route::rule('api/:version/order', 'api/:version.Order/placeOrder', 'POST');