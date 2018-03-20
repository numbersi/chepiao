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

Route::get('/', function () {
   $user = App\User::first();
    dd(auth()->login($user));
    return view('welcome');
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
        $router::post('/getInfo', 'LoginController@getInfo');
        $router::post('/payT', 'OrderController@payT');
        $router::post('/getOrderByUser', 'OrderController@getOrderByUser');
        $router::post('/getChecked', 'CheckOrderController@getChecked');
        $router::post('/checkTicket', 'CheckOrderController@checkTicket');
    });
});