<?php

namespace App\Http\Controllers;

use App\ClientProfile;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

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
        $check = User::where('id', $request->id)->first();

        if ($check) {
            $edit =false;
            $request->user()->id == $check->user_id ? $edit= true:null;
            $collections = User::where('id', $check->id)->with('clientProfile', 'pets')
                ->get();
            foreach ($collections as $collection) {
                $user["name"] = $collection->name;
                $user["lastname"] = $collection->lastname;
                $user["email"] = $collection->email;
                $profile["id"] = $collection->clientProfile->id;
                $profile["about"] = $collection->clientProfile->about;
                $profile["address"] = $collection->clientProfile->address;
                $profile["phone"] = $collection->clientProfile->phone;
                $profile["image"] = url("profileClients/".$collection->clientProfile->image);
                if ($collection->pets) {
                    $i = 0;
                    foreach ($collection->pets as $pet) {
                        $pets[$i]["id"] = $pet->id;
                        $pets[$i]["name"] = $pet->name;
                        $pets[$i]["image"] = url("pets/".$pet->image);
                        $i++;
                    }
                }
            }
            return response(['user' => $user, 'profile' => $profile, 'pets' => $pets, "edit"=>$edit], 200);
        } else {
            return response(['error' => 'El perfil no existe'], 200);
        }
    }
}
