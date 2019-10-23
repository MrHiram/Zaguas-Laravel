<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Redirect; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function resetRedirect($token)
    {
        $user = User::where('remember_token',$token)->first();

        if($user){
            $response = 'Token valido';
            $url= "exp://192.168.43.156:19000/--/resetPassword/".$token;
            return Redirect::to($url);
        }else{
            $url= "exp://192.168.43.156:19000/--/resetPassword/INVALID";
            return Redirect::to($url);
        }


    }

    public function resetPassword(Request $request)
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
                return response(['error'=>$response]);
              }
            $user->remember_token = '';
            $user->password= $newPassword;
            $user->updated_at = date('Y-m-d H:i:s');
            $user->save();
        
        //posibilidad de redirigir con un enlace simbolico al usuario y loguearlo al verificarse.
        //$token = $user->createToken('Laravel Password Grant Client')->accessToken;
        //$response = ['token' => $token];
        //return $user;
             $response= 'Password changed';
            return response(['message'=>$response],200);
        }
    }
}
