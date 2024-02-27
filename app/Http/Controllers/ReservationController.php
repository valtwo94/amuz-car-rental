<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ReservationController extends Controller
{

    function list()
    {
        $userId = Auth::user()->id;
        $reservations = Reservation::with('car')->where('user_id', $userId)->where('status', 'fixed')->get();

        return Inertia::render('Dashboard', [
            'reservations' => $reservations
        ]);
    }

    function create(Request $request)
    {
        $cars = Car::all();
        $reservation_id = $request->input('reservation_id');
        $reservation =Reservation::with('car')->find($reservation_id);


        return Inertia::render('Reservation/Create', [
            'reservation' => $reservation,

        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'pickup_time'=>'required|date',
            'pay_method'=>'required|string',
            'pickup_region'=>'required|string',
            'reservation_id'=>'required|integer'
        ]);

        $reservation_id = $request->input('reservation_id');
        $reservation = Reservation::find($reservation_id);
        if($reservation){
            $reservation->update([
                'pickup_time' => $request->input('pickup_time'),
                'pay_method' => $request->input('pay_method'),
                'pickup_region' => $request->input('pickup_region'),
                'status'=>'fixed'
            ]);

            return response()->json(['message'=>'예약성공']);
        }else{
            return response()->json(['message'=>'예약오류']);
        }
    }

    function reservationCheck(Request $request)
    {

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'car_id' => 'required|exists:cars,id',
            'total_price' => 'required|int'
        ]);

        $user = auth()->user();

        $overlappingReservation = Reservation::where('car_id', $request->input('car_id'))
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->input('start_date'), $request->input('end_date')])
                    ->orWhereBetween('end_date', [$request->input('start_date'), $request->input('end_date')])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_date', '<', $request->input('start_date'))
                            ->where('end_date', '>', $request->input('end_date'));
                    });
            })
            ->first();


        if ($overlappingReservation) {
            return response()->json(['message' => '예약불가', 'reservationId' => null]);
        }

        $reservation = Reservation::create([
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'car_id' => $request->input('car_id'),
            'status' => 'temporary',
            'user_id' => $user->id,
            'total_price' =>$request->input('total_price'),
            'confirmation_time' => Carbon::now()->addMinutes(10)
        ]);

        if ($reservation) {
            return response()->json(['message' => '예약가능', 'reservationId' => $reservation->id]);
        }
        return response()->json(['message' => '예약가능', 'reservationId' => null]);


    }

    function reservationUpdate(Request $request)
    {


        $user = auth()->user();

        $reservationId = $request->get('reservation_id');

        $previousReservation = Reservation::find($reservationId);

        if ($previousReservation) {
            $previousReservation->delete();
            $overlappingReservation = Reservation::where('car_id', $request->input('car_id'))
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->input('start_date'), $request->input('end_date')])
                        ->orWhereBetween('end_date', [$request->input('start_date'), $request->input('end_date')])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('start_date', '<', $request->input('start_date'))
                                ->where('end_date', '>', $request->input('end_date'));
                        });
                })
                ->first();


            if ($overlappingReservation) {
                return response()->json(['message' => '예약불가', 'reservationId' => null]);
            }

            $reservation = Reservation::create([
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'car_id' => $request->input('car_id'),
                'status' => 'temporary',
                'user_id' => $user->id,
                'total_price' =>$request->input('total_price'),
                'confirmation_time' => Carbon::now()->addMinutes(5)
            ]);

            if ($reservation) {
                return response()->json(['message' => '예약가능', 'reservationId' => $reservation->id]);
            }


        }


        return response()->json(['message' => '예약불가', 'reservationId' => null]);

    }


}
