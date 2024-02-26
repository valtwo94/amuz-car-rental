<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReservationController extends Controller
{

    function list()
    {
        $reservations = Reservation::all();

        return Inertia::render('Dashboard', [
            'reservations' => $reservations
        ]);
    }

    function create()
    {
        $cars = Car::all();

        return Inertia::render('Reservation/Create', [
            'cars' => $cars
        ]);
    }

    public function store(Request $request)
    {

        return redirect('/'); // Redirect to the desired page after creating reservation
    }

    function reservationStartDateCheck(Request $request)
    {

    }

    function reservationCheck(Request $request)
    {

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'car_id' => 'required|exists:cars,id',
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
                'confirmation_time' => Carbon::now()->addMinutes(5)
            ]);

            if ($reservation) {
                return response()->json(['message' => '예약가능', 'reservationId' => $reservation->id]);
            }


        }


        return response()->json(['message' => '예약불가', 'reservationId' => null]);

    }

    function updateToFixedReservation(Request $request)
    {

    }
}
