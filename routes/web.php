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

use Illuminate\Support\Facades\App;

Route::get('/', function () {
    App::abort(404);
});
Route::get('/token', function () {
    return auth()->user();
})->middleware('refresh.token');

Route::post('/token', 'LoginController@login');



Route::group([

    'prefix' => 'api'

], function ($router) {
    $router->post('notifyUrl', 'WeChatController@notifyUrl');
    $router::post('getToken', 'LoginController@getToken');
    $router::get('/getHomeInfo', 'LoginController@getHomeInfo');
    $router::get('/getPrice', 'LoginController@getPrice');
    $router::get('/getTickets', 'TicketController@getTickets');

    $router::group([
        'middleware'=>('refresh.token')
    ], function ($router) {
        $router::post('/getUserInfo', 'LoginController@getUserInfo');
        $router::post('/payT', 'OrderController@payT');
        $router::post('/getOrderByUser', 'OrderController@getOrderByUser');
        $router::post('/getChecked', 'CheckOrderController@getChecked');
        $router::post('/checkTicket', 'CheckOrderController@checkTicket');
        $router::post('/getPhone', 'WeChatController@getPhone');
    });
});


Route::group([
    'prefix' => 'admin',
'namespace' => 'Admin'
], function ($router) {
    $router::get('/index','IndexController@index');
    $router::post('/login','LoginController@login');
    $router::get('/login','LoginController@index');
    $router::get('/order/paginate','OrderController@paginate');
    $router::put('/order/check','OrderController@check');
    $router::group([
//        'middleware' => ('adminToken')
    ], function ($router) {
        $router::put('/order/check','OrderController@check');
    });
});