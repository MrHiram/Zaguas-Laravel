<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
class AuthController extends Controller
{
    public function check(Request $request){
        return response(['message'=>'There are patos'], 200);
    }
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}