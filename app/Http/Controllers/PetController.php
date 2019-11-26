<?php

namespace App\Http\Controllers;

use App\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


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
    public function show(Pet $pet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $pet=Pet::where('id',1)->first();

        if($request->user()->id === $pet->user_id){
            return response('edit',200);
        }
        return response('no',422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pet $pet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pet $pet)
    {
        //
    }
}