<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Notifications\SignupActivate;
class AuthController extends Controller
{
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}