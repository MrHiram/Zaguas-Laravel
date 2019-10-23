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
            $response = 'email sent';
            return response(['message' => $response],200);

        }else{
            $response = 'Este correo no existe';
            return response(['message' => $response],401);
        }
    }

    public function forgotPasswordActivateCheck($token){
        $user = User::where('remember_token',$token)->first();

        if($user){
            $response = 'Token valido';
            return response(['message'=> $response],200);
        }else{
            $response = 'Token invalido';
            return response(['message'=> $response],404);
        }
    }
    public function forgotPasswordActivate(Request $request)
    {
        $valitatedData = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $newPassword=Hash::make($request['password']);

        if($valitatedData->fails()){
            return response(['error'=>$valitatedData->errors()->all()]);
        }else{
            $user = User::where('remember_token', $request->token)->first();
             if (!$user) {
                 $response= 'This activation token is invalid.';
                return response($response, 404);
              }
            $user->remember_token = '';
            $user->password= $newPassword;
            $user->udated_at = date('Y-m-d H:i:s');
            $user->save;
        
        //posibilidad de redirigir con un enlace simbolico al usuario y loguearlo al verificarse.
        //$token = $user->createToken('Laravel Password Grant Client')->accessToken;
        //$response = ['token' => $token];
        //return $user;
             $response= 'Contraseña cambiada corrrectamente';
            return response(['message'=>$response],200);
        }
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
