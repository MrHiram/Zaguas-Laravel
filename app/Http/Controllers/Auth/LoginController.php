<?php

namespace App\Http\Controllers\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    
    use AuthenticatesUsers, IssueTokenTrait;
    
    private $user;
	public function __construct(){
		$this->user = User::find(1);
	}

    public function login (Request $request) {

        $valitatedData = Validator::make($request->all(), [
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if($valitatedData->fails()){
            return response(['error'=>$valitatedData->errors()->all()]);
        }else{
            if(auth()->attempt($request->all())){
                $accessToken = auth()->user()->createToken('authToken')->accessToken;
                return response(['user' => auth()->user(), 'accessToken'=>$accessToken], 200);
            }else{
                $user = User::where('email', $request->email)->first();
                if (!$user) {
                    return response(['error'=>['User does not exist']]);
                }else{
                    return response(['error'=>['Invalid credentials']]);
                }
            }
        }
    }

    public function logout (Request $request) {

        $token = $request->user()->token();
        $token->revoke();
    
        $response = 'You have been succesfully logged out!';
        return response(['message'=>$response], 200);
    
    }

    public function refresh(Request $request){
    	$this->validate($request, [
    		'refresh_token' => 'required'
    	]);
    	return $this->issueToken($request, 'refresh_token');
    }
}
