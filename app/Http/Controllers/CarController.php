<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class CarController extends Controller
{
    function create()
    {
        return Inertia::render('Car/Create');
    }

    function show($id)
    {
        $car = Car::find($id);
        return Inertia::render('Car/Show', [
            'car'=> $car
        ]);
    }

    function store(Request $request)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'year' => 'required|string|max:4',
            'price' => 'required|numeric|min:0',
            'company' => 'required|string|max:255',
            'mileage' => 'required|numeric|min:0',
            'imageUrl' => 'required|url',
            'status' => 'required|in:available,unavailable,maintenance',
        ]);

        Car::create($request->all());

        return redirect('/')->with('success', '차량이 성공적으로 생성되었습니다.');
    }

    function list(){

        $cars = Car::all();

        return Inertia::render('Car/List', [
            'canLogin' => Route::has('login'),
            'cars'=> $cars,
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
