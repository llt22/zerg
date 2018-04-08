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