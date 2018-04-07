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
Route::rule('api/v1/banner/:id/', 'api/v1.Banner/getBanner', 'GET');
Route::rule('api/v1/theme/', 'api/v1.Theme/getSimpleList', 'GET');