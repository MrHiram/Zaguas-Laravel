<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Notifications\SignupActivate;
class AuthController extends Controller
{
    public function register (Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' =>'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
    
        $request['password']=Hash::make($request['password']);
        $request['activation_token'] = str_random(60);
        $user = User::create($request->toArray());
    
        //$token = $user->createToken('Laravel Password Grant Client')->accessToken;
        //$response = ['token' => $token];
        $response="user created";
        $user->notify(new SignupActivate($user));
        return response($response, 200);
    
    }
    public function signupActivate($token)
{
    $user = User::where('activation_token', $token)->first();
    if (!$user) {
        $response= 'This activation token is invalid.';
        return response($response, 404);
    }
    $user->active = true;
    $user->activation_token = '';
    
    $user->email_verified_at = date('Y-m-d H:i:s');
    $user->save();
    //posibilidad de redirigir con un enlace simbolico al usuario y loguearlo al verificarse.
    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
    $response = ['token' => $token];
    return $user;
}

    

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}