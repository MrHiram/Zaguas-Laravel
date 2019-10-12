<?php

namespace App\Http\Controllers\Auth;
use App\Notifications\PasswordResetNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\User;

class ForgotPasswordController extends Controller
{
 

    public function forgotPassword($email){
        $user = User::where('email', $email)->first();
        
        if($user){
            $token =str_random(60);
            $user->remember_token=$token;
            $user->save();
            $user->notify(new PasswordResetNotification($user));
            $response = 'Su recuperacion de contraseña está en proceso, por favor verifique su correo';
            return response($response,200);

        }else{
            $response = 'Este correo no existe';
            return response($response,422);
        }
    }

    public function forgotPasswordActivate($token)
    {
        $user = User::where('remember_token', $token)->first();
        if (!$user) {
            $response= 'This activation token is invalid.';
            return response($response, 404);
        }
        $user->remember_token = '';
        $user->udated_at = date('Y-m-d H:i:s');
        //$user->save();
        //posibilidad de redirigir con un enlace simbolico al usuario y loguearlo al verificarse.
        //$token = $user->createToken('Laravel Password Grant Client')->accessToken;
        //$response = ['token' => $token];
        //return $user;
        $url= 'http://www.patos.com';
        return Redirect::to($url);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*
    public function __construct()
    {
        $this->middleware('guest');
    }
    */
}
