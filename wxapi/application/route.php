<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;


Route::post('login','api/login/login');
Route::post('checktoken','api/login/checktoken');


// 公众号
Route::get('handle', 'api/HandleWx/getwxlist');
Route::post('handle', 'api/HandleWx/addwx');
Route::put('handle', 'api/HandleWx/updatewx');
Route::delete('handle', 'api/HandleWx/deletewx');

// 文章
Route::get('article', 'api/article/index');
Route::post('article', 'api/article/save');
Route::put('article', 'api/article/update');
Route::delete('article', 'api/article/delete');


// 留言
Route::get('msg', 'api/message/getmsg');
