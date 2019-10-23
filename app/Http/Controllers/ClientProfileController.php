<?php

namespace App\Http\Controllers;

use App\ClientProfile;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ClientProfileController extends Controller
{
    public function register(Request $request){
        $valitatedData = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'about' =>'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string|max:255',
        ]);

        

        if($valitatedData->fails()){
            return response(['error'=>$valitatedData->errors()->all()]);
        }else{
            $clientProfile = new ClientProfile();
            //subir imagen
            try {
                $image= $request->file('image');
                $extension = $image->getClientOriginalExtension(); 
                $fileName = time().'.'.$extension;
                $path = public_path().'/profileClients';
                $image->move($path, $fileName);
                
            } catch (Exception $e) {
                return response(['error'=> $e],500);
            }
            
            $clientProfile->image = $fileName ;
            $clientProfile ->user_id= $request->user()->id;
            $clientProfile->about =  $request->about;
            $clientProfile->phone = $request->phone;
            $clientProfile->address = $request->address;
            $clientProfile->save();
            //asociar a tabla roles
            $role=Role::where('name','client')->first();
            $user = $request->user();
            $user->roles()->attach($role);
            return response(['message' => 'Profile client created']);
        }

    }

    
}
