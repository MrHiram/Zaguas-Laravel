<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['middleware' => ['json.response']], function () {
    Route::get('forgotPassword/{email}', 'Auth\ForgotPasswordController@forgotPassword');
    Route::get('resetPassword/{token}', 'Auth\ForgotPasswordController@forgotPasswordActivate');
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        //modificar segun la necesidad que requiera
        $data = ["id" => $request->user()->id, "correo" =>$request->user()->email ,"nombre" =>$request->user()->name];
        return $data;
    });

    // public routes
    Route::post('/login', 'Auth\LoginController@login')->name('login.api');
    Route::post('/register', 'AuthController@register')->name('register.api');
    Route::post('/auth/token','Auth\LoginController@refresh');

    // private routes
    Route::middleware('auth:api')->group(function () {
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    });
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
    
});
