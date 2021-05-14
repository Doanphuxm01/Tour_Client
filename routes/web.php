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

use App\Elibs\eView;
use App\Elibs\Helper;
use App\Http\Models\Customer;
use App\Http\Models\Transaction;
use Carbon\Carbon;

if (config('debugbar.enabled')) {
    \Illuminate\Support\Facades\DB::connection()->enableQueryLog();
}
if (config('app.env') != 'production') {
    define('__DEV__', true);
}else {
    define('__DEV__', false);
}

Route::any('public_api/{api_class?}/{api_func?}', 'API\AppApi@public_api');
Route::any('_crawl/{class?}/{method?}', '_Dev\CrawlDev@index');
Route::any('_auto/{class?}/{method?}', '_Dev\DevData@run');

// Route ForntEnd
Route::group(['middleware' => ['web'], 'prefix' => '/', 'namespace' => 'FrontEnd',], function () {

    Route::any('/', ['as' => 'FeHome', 'uses' => 'FeHome\FeHome@index']);
    Route::any('/subscribe', ['as' => 'FeSubscribe', 'uses' => 'FeSubscribe\FeSubscribe@_save']);
    Route::any('search', ['as' => 'FeHome.Search', 'uses' => 'FeHome\FeHome@search']);
    Route::group(['middleware'=> ['admin'],'prefix' => 'info','before'=>''], function(){
        Route::post('_save',['as' => 'FeInfoSave','uses'=>'FeInfo\FeInfo@_save']);
        Route::get('/',['as'=> 'Member','uses'=>'FeInfo\FeInfo@index']);
    });

    Route::group(['prefix' => 'booking', 'before' => ''], function () {
        Route::post('_save', ['as' => 'FeBookingSave', 'uses' => 'FeBooking\FeBooking@_save']);
        Route::post('_save_cart', ['as' => 'FeBookingSaveCart', 'uses' => 'FeBooking\FeBooking@_save_cart']);
        Route::any('{id}', ['as' => 'FeBooking', 'uses' => 'FeBooking\FeBooking@index']);
        Route::group(['prefix' => 'checkout', 'before' => ''], function () {
            Route::any('{action_name?}', ['as' => 'FeBookingCart', 'uses' => 'FeBooking\FeCart@index']);
        });
        Route::any('w/{id}', ['as' => 'FeBookingTourLe', 'uses' => 'FeBooking\FeBooking@tour_le']);
        Route::get('search/{id}', ['as' => 'FeBookingSearch', 'uses' => 'FeBooking\FeBooking@detail']);

    });

    


    Route::any('{alias}.html', ['as' => 'FeTour', 'uses' => 'FeTours\FeTour@index'])->where(['alias' => '[a-zA-Z0-9_\-]+',]); // danh sách tour
    Route::any('place/{alias}', ['as' => 'FeTour.Place', 'uses' => 'FeTours\FeTour@place'])->where(['alias' => '[a-zA-Z0-9_\-]+',]);
    Route::any('combo/{alias}', ['as' => 'FeTour.Combo', 'uses' => 'FeTours\FeTour@combo'])->where(['alias' => '[a-zA-Z0-9_\-]+',]);
    // route tin tuc
    Route::group(['prefix' => '{category}', 'as' => 'FeContent.', 'before' => ''], function () {
        Route::get('/', ['as' => 'NewsCate', 'uses' => 'FeContent\FeNews@index']);
        Route::get('{alias}.html', ['as' => 'NewsDetail', 'uses' => 'FeContent\FeNews@input'])->where(['alias' => '[a-zA-Z0-9_\-]+',]);
    });




});

require_once ('admin.php');


