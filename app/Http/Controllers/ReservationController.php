<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\Home;
use App\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
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

        public function create(Request $request)
    {
        if($request->user()->hasRole(["client"])){
            $validator = Validator::make($request->all(), [
                'home_id' =>'required|max:120',
                'start_date' => 'required|date',
                'end_date' => 'required|date|',
                'pets_id' => 'required|array|'
            ]);
        
            if ($validator->fails())
            {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
            //return response($request->all());
            $home = Home::where("id",$request->home_id)->first();
            $reservation = new Reservation;
            $reservation->client_profile_id =$request->user()->getIdProfileClient();
            $reservation->care_taker_profile_id =$home->care_taker_profile_id;
            $reservation->home_id =$home->id;;
            $reservation->start_date =$request->start_date;
            $reservation->end_date =$request->end_date;
            $reservation->status ='pendiente';
            
            $reservation->save();

            foreach($request->pets_id as $petId){
                $pet = Pet::where("id",$petId)->first();
                $reservation->pets()->attach($pet);

            }
            
            return response(["message" =>"Reservacion  creada"], 200);

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
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $reservations =Reservation::where("client_profile_id",$request->user()->getIdProfileClient())
        ->with('home','careTaker.user')->get();
        
       $defi= $reservations->map(function($item) {
            return [
                'careTakerImage' =>url('profileCareTaker/'.$item->careTaker->image),
                'homeImage'=>url('homes/'.$item->home->image),
                'price_per_night'=>$item->home->price_per_night,
                'ownerLocation'=>$item->careTaker->address,
                'start_date'=>$item->start_date,
                'careTakerName'=>$item->careTaker->user->name,
                'end_date'=>$item->end_date,
                'status'=>$item->status,
                'id' => $item->id,
            ];
            
    });
            
        //}
       
        return response(["reservations" => $defi],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
