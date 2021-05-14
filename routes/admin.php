<?php
Route::any('auth/{action_name?}', ['as' => 'AuthGate', 'uses' => 'AdminMember\MemberGate@index']);

Route::group(['middleware' => ['admin'], 'prefix' => '/admin', 'before' => ''], function () {
    Route::any('/info/{action_name?}', ['as' => 'AdminSystem', 'uses' => 'AdminSystem\MngSystem@index']);
    Route::get('/demo_sendmail', ['as' => 'AdminSystemMail', 'uses' => 'AdminSystem\AdminSystem@demo_send_mail']);
    Route::any('/booking/{action_name?}', ['as' => 'AdminBooking', 'uses' => 'AdminBooking\MngBooking@index']);
    Route::any('/msg/{action_name?}', ['as' => 'AdminMsg', 'uses' => 'AdminMsg\MngMsg@index']);
    Route::any('/staff/{action_name?}', ['as' => 'AdminMember', 'uses' => 'AdminMember\MngMember@index']);
    Route::any('/notification/{action_name?}', ['as' => 'AdminNotification', 'uses' => 'AdminNotification\MngNotification@index']);

    Route::post('media/do-upload', 'AdminContent\MngMedia@do_upload');
});