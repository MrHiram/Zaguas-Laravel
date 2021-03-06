<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Notifications\SignupActivate;
use Illuminate\Support\Facades\Redirect;   
use Illuminate\Routing\UrlGenerator; 

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request){
        $valitatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' =>'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $request['password']=Hash::make($request['password']);

        if($valitatedData->fails()){
            return response(['error'=>$valitatedData->errors()->all()]);
        }else{
            $user = new User();
            $user->name = $request->name;
            $user->lastname =  $request->lastname;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->activation_token = str_random(60);
            $user->save();
            
            $user->notify(new SignupActivate($user));
            return response(['message' => 'user created']);
        }
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
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];
        $urlRaw= url("/--/validateEmail/".$token);
        $urlReplace =str_replace(
            array("http://","8000"),
            array("exp://", "19000"),
            $urlRaw
        );
        return Redirect::to($urlReplace);
    }
}
