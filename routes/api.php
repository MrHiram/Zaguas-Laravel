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



   
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        //modificar segun la necesidad que requiera
        $data = ["id" => $request->user()->id, "correo" =>$request->user()->email ,"nombre" =>$request->user()->name];
        return $data;
    });
    Route::get('prueba', function(){return response('patos',200);});
    // public routes
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/register', 'Auth\RegisterController@register')->name('register.api');
    Route::post('/auth/token','Auth\LoginController@refresh');
    Route::post('resetPassword', 'Auth\ResetPasswordController@resetPassword');

    //forgot password 
    Route::get('checkForgotPasswordToken/{token}', 'Auth\ForgotPasswordController@forgotPasswordActivateCheck');
    Route::get('forgotPassword/{email}', 'Auth\ForgotPasswordController@forgotPassword');
    
    // private routes
    
    Route::middleware('auth:api')->post('/logout', 'Auth\LoginController@logout');
    Route::middleware('auth:api')->get('/checkToken', 'AuthController@check');

        //crud mascota
    Route::middleware('auth:api')->post('addPet','PetController@create');
    Route::middleware('auth:api')->post('editPet','PetController@edit');
    Route::middleware('auth:api')->post('assignClientRole', 'ClientProfileController@register');
    
    Route::get('signup/activate/{token}', 'Auth\RegisterController@signupActivate');
    Route::get('reset/redirect/{token}', 'Auth\ResetPasswordController@resetRedirect');