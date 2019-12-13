<?php

namespace App\Http\Controllers;

use App\ClientProfile;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use File;

class ClientProfileController extends Controller
{
    public function register(Request $request)
    {
        $valitatedData = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'aboutMe' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string|max:255',
        ]);



        if ($valitatedData->fails()) {
            return response(['error' => $valitatedData->errors()->all()]);
        } else {
            $clientProfile = new ClientProfile();
            //subir imagen
            try {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $path = public_path() . '/profileClients';
                $image->move($path, $fileName);
            } catch (Exception $e) {
                return response(['error' => $e], 500);
            }

            $clientProfile->image = $fileName;
            $clientProfile->user_id = $request->user()->id;
            $clientProfile->about =  $request->aboutMe;
            $clientProfile->phone = $request->phone;
            $clientProfile->address = $request->address;
            $clientProfile->save();
            //asociar a tabla roles
            $role = Role::where('name', 'client')->first();
            $user = $request->user();
            $user->roles()->attach($role);
            return response(['message' => 'Profile client created'], 200);
        }
    }

    public function getProfile(Request $request)
    {
        $check = ClientProfile::where('id', $request->id)->first();

        if ($check) {
            $edit =false;
            $request->user()->id == $check->user_id ? $edit= true:null;
            $collections = ClientProfile::where('id', $check->id)->with('user', 'pets')
                ->get();
            
            foreach ($collections as $collection) {
                $user["name"] = $collection->user->name;
                $user["lastname"] = $collection->user->lastname;
                $user["email"] = $collection->user->email;
                $profile["id"] = $collection->id;
                $profile["about"] = $collection->about;
                $profile["address"] = $collection->address;
                $profile["phone"] = $collection->phone;
                $profile["image"] = url("profileClients/".$collection->image);
               
                if (count($collection->pets) > 0) {
                    $i = 0;
                    foreach ($collection->pets as $pet) {
                        $pets[$i]["id"] = $pet->id;
                        $pets[$i]["name"] = $pet->name;
                        $pets[$i]["image"] = url("pets/".$pet->image);
                        $i++;
                    }
                    return response(['user' => $user, 'profile' => $profile, 'pets' => $pets, "edit"=>$edit], 200);
                }
            }
            return response(['user' => $user, 'profile' => $profile,  "edit"=>$edit], 200);
        } else {
            return response(['error' => 'El perfil no existe'], 200);
        }
    }

    public function getClientProfileID(Request $request){
        return response(["id" => $request->user()->getIdProfileClient()],200);

    }

    public function delete(Request $request){
        $profile = ClientProfile::where("id",$request->id)->first();
         if($profile){
             if($request->user()->id === $profile->user_id){
                $image_path = "profileClients/". $profile ->image;  
                $this->deleteFile($image_path);
                $profile->delete();
                return response(["message" => "Perfil cliente eliminado borrado"],200);

             }else{
                return response(["error" => "No eres el usario de este perfil"],401);
             }
         }
         return response(["error" => "Perfil cliente no existe no existe"],404);
    }

    public function edit(Request $request){
        if($request->user()->hasRole(["client"])){
            $validator = Validator::make($request->all(), [
                'edit' => 'required',
                'image' => '|image|mimes:jpeg,png,jpg|max:2048',
                'aboutMe' => '|string|max:255',
                'phone' => '|string',
                'address' => '|string|max:255',
        ]);
    
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $clientProfile = ClientProfile::where("id",$request->id)->first();
        if($request->user()->getIdProfileClient() === $clientProfile->client_profile_id){
            $array =$request->all();
            foreach($array as $key => $value)
            {
                
                if($key == 'image'){
                    $image= $request->file('image');
                    $extension = $image->getClientOriginalExtension(); 
                    $fileName = time().'.'.$extension;
                    $path = public_path().'/profileClients';
                    $image_path = 'profileClients/'. $clientProfile ->$key;  
                    $clientProfile ->$key = $fileName ;
                    $image->move($path, $fileName);
                    $this->deleteFile($image_path);
                    continue;
                }
                if($key == 'id') continue;

                $clientProfile ->$key=$value;
                
            
            }
                $clientProfile->save();
                return response($clientProfile, 200);
            }else{
                return response(["error" => "No eres el dueno de este perfil"],401);
            }
        }else{
            return response(["message" => "No tienes la autorizacion para realizar esta accion."], 401);
        }
    }

    public function deleteFile($image_path){
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
    }
}
