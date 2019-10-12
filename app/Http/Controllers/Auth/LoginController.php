<?php

namespace App\Http\Controllers\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    

    use AuthenticatesUsers, IssueTokenTrait;
    
    private $user;
	public function __construct(){
		$this->user = User::find(1);
	}

    public function login (Request $request) {

        $user = User::where('email', $request->email)->first();
    
        if ($user) {
    
            if (Hash::check($request->password, $user->password)) {
               //verificacion si el usuario esta activo
                if($user->active){
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    $response = ['token' => $token];
                    return response($response, 200);
                }else{
                    $response = "usuario no verificado";
                    return response($response,422);
                }
                
            } else {
                $response = "Password missmatch";
                return response($response, 422);
            }
    
        } else {
            $response = 'User does not exist';
            return response($response, 422);
        }
    
    }

    public function logout (Request $request) {

        $token = $request->user()->token();
        $token->revoke();
    
        $response = 'You have been succesfully logged out!';
        return response($response, 200);
    
    }

    public function refresh(Request $request){
    	$this->validate($request, [
    		'refresh_token' => 'required'
    	]);
    	return $this->issueToken($request, 'refresh_token');
    }
}
