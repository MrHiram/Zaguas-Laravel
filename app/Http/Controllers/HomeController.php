<?php

namespace App\Http\Controllers;

use App\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use File;

class HomeController extends Controller
{
    public function create(Request $request)
    {
        
        if($request->user()->hasRole(["care_taker"])){
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' =>'required|string|max:120',
                'price_per_night' => 'required|digits_between:1,6',
                'capacity' => 'required|digits_between:1,2|',
                'walk' => 'required|boolean',
                'days_available' => 'required|string|min:1|max:7',
            ]);
        
            if ($validator->fails())
            {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
            $image= $request->file('image');
            $home = new Home;
            $home ->user_id= $request->user()->id;
            $extension = $image->getClientOriginalExtension(); // you can also use file name
            $fileName = time().'.'.$extension;
            $path = public_path().'/homes';
            $home ->image = $fileName ;
            $image->move($path, $fileName);
            $home ->description=$request->description ;
            $home ->price_per_night=$request->price_per_night;
            $home ->capacity=$request->capacity;
            $home ->walk=$request->walk ;
            $home ->days_available=$request->days_available;
            $home->save();
            return response($home, 200);

        }else{
            return response(["message" => "No tienes la autorizacion para realizar esta accion."], 401);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Home  $pet
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $home = Home::where('id', $request->id)->with('careTaker')->first();

        if($home){
            $edit =false;
            $request->user()->id == $home->user_id ? $edit= true: null;
            return response(["Home" => $home, "edit"=> $edit],200);

        }else{
            return response(["error" => "Home not found"],404);
        }
    }

    public function edit(Request $request){
        if($request->user()->hasRole(["care_taker"])){
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'id' => 'required',
                'description' =>'required|string|max:120',
                'price_per_night' => 'required|digits_between:1,6',
                'capacity' => 'required|digits_between:1,2|',
                'walk' => 'required|boolean',
                'days_available' => 'required|string|min:1|max:7',
            ]);
        
            if ($validator->fails())
            {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
            $home = Home::where("id",$request->id)->first();
            if($request->user()->id === $home->user_id){

                $image= $request->file('image');
                $image_path = "homes/". $home ->image;  
                deleteFile($image_path);
                $extension = $image->getClientOriginalExtension(); // you can also use file name
                $fileName = time().'.'.$extension;
                $path = public_path().'/homes';
                $home ->image = $fileName ;
                $image->move($path, $fileName);
                
                $home ->description=$request->description ;
                $home ->price_per_night=$request->price_per_night;
                $home ->capacity=$request->capacity;
                $home ->walk=$request->walk ;
                $home ->days_available=$request->days_available;
                $home->save();
                return response($home, 200);
            }else{
                return response(["error" => "No eres el cuidador de esta casa"],401);
            }
            
        }else{
            return response(["message" => "No tienes la autorizacion para realizar esta accion."], 401);
        }
    }

    public function delete(Request $request){
        
         $home = Home::where("id",$request->id)->first();
         if($home){
             if($request->user()->id === $home->user_id){
                $image_path = "homes/". $home ->image;  
                $this->deleteFile($image_path);
                $home->delete();
                return response(["message" => "Hogar borrado"],200);

             }else{
                return response(["error" => "No eres el cuidador de este hogar"],401);
             }
         }
         return response(["error" => "Hogar no existe"],404);
    }

    public function deleteFile($image_path){
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
    }
}
