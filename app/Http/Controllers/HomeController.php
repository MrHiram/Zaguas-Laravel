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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Home  $pet
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $home = Home::where('id', $request->id)->first();

        if($home){
            $collections = Home::where('id', $home->id)->with('careTaker')
            ->get();
            return response(["Home" => $collections],200);

        }else{
            return response(["error" => "Home not found"],404);
        }
    }
}
