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

//Route::rule('路由表达式','路由地址'.'请求类型','路由参数'.'变量规则');
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');
Route::get('api/:version/theme','api/:version.theme/getSimpleList');
Route::get('api/:version/theme/:id','api/:version.theme/getComplexOne');

Route::get('api/:version/product/:count','api/:version.product/getMostRecent');
Route::get('api/:version/product_category/:id','api/:version.product/getAllInCategory',[],['id'=>'\d+']);
Route::get('api/:version/product_one/:id','api/:version.product/getOne');

Route::get('api/:version/category/all','api/:version.category/getAllCategory');

Route::post('api/:version/token/user','api/:version.Token/getToken');

Route::post('api/:version/address','api/:version.address/CreateOrUpdateAddress');

Route::post('api/:version/order','api/:version.order/placeOrder');
Route::get('api/:version/order_page','api/:version.order/getSummaryByUser');

Route::post('api/:version/pay/pre_order','api/:version.pay/getPreOrder');
Route::post('api/:version/pay/receive','api/:version.pay/receiveNotify');
