<?php

use App\Http\Middleware\SuperRole;
//Auth::routes(["register"=>false]);
//Route::post('/authenticate',        'UserController@authenticate')->name('authenticate');
//Route::get('/register',        'UserController@registerTmbdUser')->name('register');
//Route::post('/register',        'UserController@registerTmbdUserSave')->name('register');
//
//Route::get('/',function(){
//    return view('auth.login');
//})->middleware(['auth']);

Route::prefix('super')->middleware([SuperRole::class])->group(function () {
    Route::get('/',                     'App\Http\Controllers\Super\DashboardController@index')->name("super.dashboard");
    Route::get('/dashboard',            'App\Http\Controllers\Super\DashboardController@index')->name("super.dashboard");
    Route::get('/users',                'App\Http\Controllers\Super\UserController@index')->name("super.users");
    Route::post('/user_datalist',       'App\Http\Controllers\Super\UserController@userList')->name("super.user_datalist");
    Route::post('/save_user',           'App\Http\Controllers\Super\UserController@saveUser')->name("super.save_user");
    Route::post('/get_user',            'App\Http\Controllers\Super\UserController@edit')->name("super.get_user");

    //sms api
    Route::get('sms-api',        'App\Http\Controllers\Super\SMSAPIController@index')->name("super.sms-api");
    Route::post('save_sms_api',  'App\Http\Controllers\Super\SMSAPIController@saveSMSAPI')->name("super.save_sms_api");
    Route::post('sms_api_list',  'App\Http\Controllers\Super\SMSAPIController@SMSAPIList')->name("super.sms_api_list");
    Route::post('sms_api_update','App\Http\Controllers\Super\SMSAPIController@SMSAPIUpdate')->name("super.sms_api_update");
    Route::post('sms_api_delete','App\Http\Controllers\Super\SMSAPIController@SMSAPIDelete')->name("super.sms_api_delete");
    
    Route::get('sms_add_balance','App\Http\Controllers\Super\SMS\BalanceController@AddBalance')->name("super.sms_add_balance");
    Route::post('sms_add_balance','App\Http\Controllers\Super\SMS\BalanceController@saveBalance')->name("super.sms_add_balance");

    Route::get('/company',              'App\Http\Controllers\Super\UserController@company')->name("super.company");
    Route::post('/client_datalist',     'App\Http\Controllers\Super\UserController@clientList')->name("super.client_datalist");
    Route::post('/company_profile',     'App\Http\Controllers\Super\UserController@company_profile')->name("super.company_profile");
    Route::post('/save_company',        'App\Http\Controllers\Super\UserController@save_client')->name("super.save_company");
    Route::post('/reset_password',        'App\Http\Controllers\Super\UserController@reset_password')->name("super.reset_password");
    Route::post('/sms_api_set',        'App\Http\Controllers\Super\UserController@company_sms_api_set')->name("super.sms_api_set");


});