<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use App\Http\Modules\YahooOAuth2;


Route::get('/','PageController@home');
Route::get('/gemini-countries', function () {
    $client = new \GuzzleHttp\Client();
    $response = $client->get("https://api.gemini.yahoo.com/v2/rest/dictionary/woeid/?type=state",
        ['headers'  => ['Authorization' =>'Bearer '.'2IkHLWSZs12jO8.iVNc0OHJel0QlHv_2Z.5_GD3NUJ8TyxlfLSl.8i2_v8DjYEfN4POOXDd7Bx4g9RP_KK.91evpm6qgBb0P_myXOc3Gto0mq3i6dstzOqLxRt1NvbCA1jumUGbxgGrA1lxl6ma8yaFiXhuv4F5uxHzYpAnyd_1FJKqYx90Gpno0SnH0ZDO2w3TMdUyU6T6vD53CYjRvxXJKjerzSBqrg9afuteH87oPsv0VFggFUZPhIUNEE9X7CCyA9SMPlF9U6ypV10zG04GiHmdD9IWfG1i5Ku_v3scGHA4n8ZxpVRYrzUJXm6ocpNBQ137G.IkCGhkWY.MIpMV_NTzZBO6WQrnuzZ0ySuAv3ivqQ79wSoy5vBMDbO.4GDqdq7nHq5S1bNpJae3pN7I.F0J5A4Byr6iLKQ0T4MyOTZJDu_9zJbQVZEeP6p.2OaBl9K97LAOCjROLWYESEFkzz3faXiL3k0iwRZy5A3K_ERXZoIOyD5rK5bN6F6ZHByNDAO7o7qOtnC2khbVSSV2YuKvsGxX_OmmGCBtJ.K9cb8MqcaDJyMZCsZoXGF5C9UQ6hM9O9zlnhWjhBrkucza7K_0RPf_DEojbS1TIWv27Phb2w6rx5PN4bAdMm3_yydGtqUIG.4TLT.pSyH6aHtTfVCM.rIQTfZQwcj0vo3IIKCfDKtx0ibFV11YPP_AEjwjD8aErC2rc6usCFyX8cOlWk_0gA3cqHALr83TmGgT6avGQrUQK_WeEwWUiHcs1VDib3Oi_TTRwCGXkLADLO98xYGyubJg6NyISfd_KtRpT0mmRw0wWsKStR3Mc8U4g50X.cWMQA_eRvf3oqSHqg448je2Mv3C1Prx9wdcbpUoorwLEzboOXWUIY0z0IiX06e4aBH1wyPn_lcWxyhrQCmK.HDPH2pC0aiQ2NP6JPfW7X8B6AFnt4nEItVe389i9JH_mJcmKhzSIRMaOw6CMiE4fO3tEDkwZIVqh9BNFYdw3cfMwWmWBptiWhGkUyMUnfBWsHOjpO2kKR3C5PVMhMJP6LvuEa.NzfZeUrIhcPwZOErwiiEXtyRcNHJBIefHKXCd5a68pOreN3ukaDMcV9Ged46HCSqB_m6PQPndq3eiUig--']]
);
    return $response->getBody()->getContents();
});
Route::get('/new','PageController@newToken');
Route::get('/update','PageController@update');
Route::get('/test/{object?}/{id?}','PageController@test');
Route::get('/api/{object?}/{param?}','PageController@apiTest');
Route::get('/status/{token?}/{id?}','PageController@status');
Route::get('/download/{token?}','PageController@downloadBulk');

Route::get('/{id?}/{name?}/objects','PageController@objects');
Route::get('/{id?}/{object?}/sync','PageController@sync');
Route::get('/{id?}/{object?}/delete','PageController@delete');

Route::get('/account-new','PageController@newAccount');
Route::post('/save-account','PageController@saveAccount');
Route::get('/delete-account/{id}','PageController@deleteAccount');
