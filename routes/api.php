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



   
    
    Route::get('prueba', function(){return response('patos',200);});
    // public routes
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/register', 'Auth\RegisterController@register')->name('register.api');
    Route::post('/auth/token','Auth\LoginController@refresh');
   
    Route::get('signup/activate/{token}', 'Auth\RegisterController@signupActivate');
    Route::get('reset/redirect/{token}', 'Auth\ResetPasswordController@resetRedirect');

    //forgot password 
    Route::get('checkForgotPasswordToken/{token}', 'Auth\ForgotPasswordController@forgotPasswordActivateCheck');
    Route::get('forgotPassword/{email}', 'Auth\ForgotPasswordController@forgotPassword');
    Route::post('resetPassword', 'Auth\ResetPasswordController@resetPassword');
    
    // private routes
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        //modificar segun la necesidad que requiera
        return response(["id" => $request->user()->id, "correo" =>$request->user()->email ,"nombre" =>$request->user()->name],200);
        
    });
    
    Route::middleware('auth:api')->post('/logout', 'Auth\LoginController@logout');
    Route::middleware('auth:api')->get('/checkToken', 'AuthController@check');
 
  

        //crud mascota
    Route::middleware('auth:api')->post('addPet','PetController@create');
    Route::middleware('auth:api')->post('editPet','PetController@edit');
    Route::middleware('auth:api')->get('pet/{id}', 'PetController@show');

    //crud profile client
    Route::middleware('auth:api')->post('assignClientRole', 'ClientProfileController@register');
    Route::middleware('auth:api')->get('getProfileClient/{id}', 'ClientProfileController@getProfile');

    //crud caretaker profile
    Route::middleware('auth:api')->post('assignCareTakerRole', 'CareTakerProfileController@register');
    Route::middleware('auth:api')->get('getProfileCareTaker/{id}', 'CareTakerProfileController@getProfile');
    //Crud homes
    
    Route::middleware('auth:api')->post('addHome','HomeController@create');
    Route::middleware('auth:api')->post('editPet','HomeController@edit');
    Route::middleware('auth:api')->get('home/{id}', 'HomeController@show');