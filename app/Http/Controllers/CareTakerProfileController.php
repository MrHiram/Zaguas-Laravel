<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\CareTakerProfile;
use App\Role;
use App\User;


class CareTakerProfileController extends Controller
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
            $careTakerProfile = new CareTakerProfile();
            //subir imagen
            try {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $path = public_path() . '/profileCareTaker';
                $image->move($path, $fileName);
            } catch (Exception $e) {
                return response(['error' => $e], 500);
            }

            $careTakerProfile->image = $fileName;
            $careTakerProfile->user_id = $request->user()->id;
            $careTakerProfile->about =  $request->aboutMe;
            $careTakerProfile->phone = $request->phone;
            $careTakerProfile->address = $request->address;
            $careTakerProfile->save();
            //asociar a tabla roles
            $role = Role::where('name', 'care_taker')->first();
            $user = $request->user();
            $user->roles()->attach($role);
            return response(['message' => 'Profile careTaker created'], 200);
        }
    }

    public function getProfile(Request $request)
    {
        $check = User::where('id', $request->id)->first();

        if ($check) {
            $edit =false;
            $request->user() ? $request->user()->id == $check->user_id ? $edit= true: null :null;
            $collections = User::where('id', $check->id)->with('careTakerProfile', 'homes')
                ->get();
            foreach ($collections as $collection) {
                $user["name"] = $collection->name;
                $user["lastname"] = $collection->lastname;
                $user["email"] = $collection->email;
                $profile["id"] = $collection->careTakerProfile->id;
                $profile["about"] = $collection->careTakerProfile->about;
                $profile["address"] = $collection->careTakerProfile->address;
                $profile["phone"] = $collection->careTakerProfile->phone;
                $profile["image"] = url("profileCareTaker/".$collection->careTakerProfile->image);
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
