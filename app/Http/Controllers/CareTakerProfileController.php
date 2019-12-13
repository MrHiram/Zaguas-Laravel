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
        $check = CareTakerProfile::where('id', $request->id)->first();

        if ($check) {
            $edit =false;
            $request->user()->id == $check->user_id ? $edit= true:null;
            $collections = CareTakerProfile::where('id', $check->id)->with('user', 'homes')
                ->get();
                
            foreach ($collections as $collection) {
                $homes =[];
                $user["name"] = $collection->user->name;
                $user["lastname"] = $collection->user->lastname;
                $user["email"] = $collection->user->email;
                $profile["id"] = $collection->id;
                $profile["about"] = $collection->about;
                $profile["address"] = $collection->address;
                $profile["phone"] = $collection->phone;
                $profile["image"] = url("profileCareTaker/".$collection->image);
                if (count($collection->homes) > 0) {
                    $i = 0;
                    foreach ($collection->homes as $home) {
                        $homes[$i]["id"] = $home->id;
                        $homes[$i]["description"] = $home->description;
                        $homes[$i]["image"] = url("pets/".$home->image);
                        $homes[$i]["price_per_night"] = $home->price_per_night;
                        $homes[$i]["capacity"] = $home->capacity;
                        $homes[$i]["walk"] = $home->walk;
                        $homes[$i]["days_available"] = $home->days_available;
                        $i++;
                    }
                    return response(['user' => $user, 'profile' => $profile, 'homes' => $homes, "edit"=>$edit], 200);
                }
            }
            return response(['user' => $user, 'profile' => $profile, "edit"=>$edit], 200);
        } else {
            return response(['error' => 'El perfil no existe'], 200);
        }
    }
}
