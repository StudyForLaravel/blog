<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
//实现自己的get路由调用
// Route::get('/testGet',function(){
//     $csrf_token = csrf_token();
//     $form = <<<FORM
//     <form action="/hello" method="get">
//     <input type="hidden" name="_token" value="{$csrf_token}">
//     <input type="submit" value="Test"/>
//     </form>
// FORM;
//     return $form;
// });
//实现自己的get路由调用
// Route::get('/hello',function(){
//     return "Hello Laravel[GET]!";
// });

//下面name后有？，代表可选；闭包中可以给name指定默认值，最后一行为name参数加上条件限制
// Route::get('/hello/{name?}',function($name="Laravel"){
//     return "Hello {$name}!";
// })->where('name','[A-Za-z]+');


//实现自己的post路由调用
//post方式必须加上csrf_token，不然会抛出异常，防止注入攻击
// Route::get('/testPost',function(){
//     $csrf_token = csrf_token();
//     $form = <<<FORM
//     <form action="/hello" method="post">
//     <input type="hidden" name="_token" value="{$csrf_token}">
//     <input type="submit" value="Test"/>
//     </form>
// FORM;
//     return $form;
// });
//实现自己的post路由调用
//不需要校验的时候，在app/Http/Middleware/VerifyCsrfToken.php中加上“hello”
// Route::post('/hello',function(){
//     return "Hello Laravel[POST]!";
// });
// Route::get('/testCsrf',function(){
//     $form = <<<FORM
//     <form action="/hello" method="post">
//     <input type="submit" value="Test"/>
//     </form>
// FORM;
//     return $form;
// });

/*
|--------------------------------------------------------------------------
| Middleware 中间件
| 1，用php artisan make:middleware TestMiddleware生成一个中间件
|       会在/app/Http/Middleware目录下生成一个TestMiddleware.php文件
| 2，打开TestMiddleware.php文件编辑TestMiddleware类的handle方法如下
| 3，打开/app/Http/Kernal.php文件，新增TestMiddleware到Kernel的$routeMiddleware 属性
|--------------------------------------------------------------------------
|
*/
// Route::group(['middleware' =>'test'],function(){
//     Route::get('/write/adult',function(){

//     });
//     Route::get('/update/team',function(){

//     });
// });
// Route::get('/age/refuse',['as' => 'refuse',function(){
//     return '未成年人禁止入内！';
// }]);

/*
|--------------------------------------------------------------------------
| HTTP控制器实例教程 —— 创建RESTFul风格控制器实现文章增删改查
|1， php artisan make:controller PostController
|2, 在 routes.php中为该控制器注册路由：Route::resource('post','PostController');
| 通过Cache进行增删改的操作
| 目的：熟悉Cache操作
| post:对应index();
| post.create: 对应 create();
| 等等……    这些都是通过route()方法进行路由生成。
|--------------------------------------------------------------------------
|
*/

// Route::resource('post','PostController');





/*
|--------------------------------------------------------------------------
| Blog 博客系统搭建
|
|
| 
|--------------------------------------------------------------------------
|
*/


Auth::routes();
Route::get('/', function () {
    return redirect('/blog');
});
Route::get('blog', 'BlogController@index');
Route::get('blog/{slug}', 'BlogController@showPost');

// Admin area
Route::get('admin', function () {
    return redirect('/admin/post');
});

/**
 *这里auth 'middleware' 是laravel在kernel中设置好的。
 *但是5.1与5.3相差很大，没有Auth\AuthController这个类。现在需要考虑的问题是如何转换AuthController到5.3的新模式中来。
 * 直接在官网中就有新特性的操作：php artisan make:auth
 * 将/auth/login后面的 Auth\AuthController@index 替换为：HomeController@index
 * 
 */

Route::group(['namespace' => 'Admin', 'middleware' => 'auth'], function () {
    Route::resource('admin/post', 'PostController', ['except' => 'show']);

    // Route::resource('admin/tag', 'TagController');
    Route::resource('admin/tag', 'TagController', ['except' => 'show']);

    Route::get('admin/upload', 'UploadController@index');
    Route::post('admin/upload/file', 'UploadController@uploadFile');
    Route::delete('admin/upload/file', 'UploadController@deleteFile');
    Route::post('admin/upload/folder', 'UploadController@createFolder');
    Route::delete('admin/upload/folder', 'UploadController@deleteFolder');
});


// Logging in and out
Route::get('/auth/login', 'HomeController@index');

Route::post('/auth/login', 'HomeController@postLogin');
Route::get('/auth/logout', 'HomeController@getLogout');


