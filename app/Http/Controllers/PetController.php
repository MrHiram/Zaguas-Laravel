<?php

namespace App\Http\Controllers;

use App\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use File;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       
        if($request->user()->hasRole(["client"])){
            $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' =>'required|string|max:120',
            'size' => 'required|string|max:120|',
            'temperament' => 'required|string|max:120|',
            'race' => 'required|string|max:120|',
            'description' => 'string|max:255|',
            'allergies' => 'string|max:255|',
            'feeding' => 'string|max:255|',
            'specials_cares' => 'string|max:255|',
        ]);
    
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $image= $request->file('image');
        $pet = new Pet;
        $pet ->client_profile_id= $request->user()->getIdProfileClient();
        $extension = $image->getClientOriginalExtension(); // you can also use file name
        $fileName = time().'.'.$extension;
        $path = public_path().'/pets';
        $pet ->image = $fileName ;
        $image->move($path, $fileName);
        $pet ->name=$request->name ;
        $pet ->size=$request->size;
        $pet ->temperament=$request->temperament;
        $pet ->race=$request->race ;
        $request->description != null ? $pet ->description=$request->description : null;
        $request->allergies != null ? $pet ->allergies=$request->allergies: null ;
        $request->feeding != null ? $pet ->feeding=$request->feeding: null ;
        $request->special_cares != null ? $pet ->allergies=$request->allergies: null ;
        $pet->save();
        return response(["message" =>"Mascota creada correctamente"], 200);
    }else{
        return response(["message" => "No tienes la autorizacion para realizar esta accion."], 401);
    }
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $pet = Pet::where('id', $request->id)->with('owner')
            ->first();

        if($pet){
            $edit =false;
            $request->$request->$request->user()->getIdProfileClient() == $pet->client_profile_id ? $edit= true:null;
            return response(["pet" => $pet, "edit" => $edit],200);

        }else{
            return response(["error" => "Pet not found"],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->user()->hasRole(["client"])){
            $validator = Validator::make($request->all(), [
            'id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' =>'string|max:120',
            'size' => 'string|max:120|',
            'temperament' => 'string|max:120|',
            'race' => 'string|max:120|',
            'description' => 'string|max:255|',
            'allergies' => 'string|nullable|max:255|',
            'feeding' => 'string|nullable|max:255|',
            'specials_cares' => 'string|nullable|max:255|',
        ]);
    
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $pet = Pet::where("id",$request->id)->first();
        if($request->user()->getIdProfileClient() === $pet->client_profile_id){
            $array =$request->all();
            foreach($array as $key => $value)
            {
                
                if($key == 'image'){
                    $image= $request->file('image');
                    $extension = $image->getClientOriginalExtension(); 
                    $fileName = time().'.'.$extension;
                    $path = public_path().'/pets';
                    $image_path = 'pets/'. $pet ->$key;  
                    $pet ->$key = $fileName ;
                    $image->move($path, $fileName);
                    $this->deleteFile($image_path);
                    continue;
                }
                if($key == 'id') continue;

                $pet ->$key=$value;
                
            
            }
                $pet->save();
                return response($pet, 200);
            }else{
                return response(["error" => "No eres el dueno de esta mascota"],401);
            }
        }else{
            return response(["message" => "No tienes la autorizacion para realizar esta accion."], 401);
        }
    
    }

    
    public function delete(Request $request)
    {
        $pet = Pet::where("id",$request->id)->first();
        if($pet){
            if($request->user()->getIdProfileClient() === $pet->client_profile_id){
                $image_path = "pets/". $pet ->image;  
                $this->deleteFile($image_path);
                $pet->delete();
                return response(["message" => "mascota borrada"],200);

            }else{
                return response(["error" => "No eres el dueno de esta mascota"],401);
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