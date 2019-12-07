<?php

namespace App\Http\Controllers;

use App\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' =>'required|string|max:120',
            'price_per_nigth' => 'required|digits:8',
            'capacity' => 'required|digits:3|',
            'walk' => 'required|boolean',
            'days_available' => 'require|array|min:1|max:7',
        ]);
    
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $image= $request->file('image');
        $pet = new Pet;
        $pet ->user_id= $request->user()->id;
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
        return response($pet, 200);
    }
}
