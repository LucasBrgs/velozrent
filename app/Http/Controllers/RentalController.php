<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Verificar se o carro já está alugado no período especificado
        $car = Car::find($request->car_id);
        $isRented = Rental::where('car_id', $car->id)
                          ->where(function($query) use ($request) {
                              $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
                          })->exists();

        if ($isRented) {
            return response()->json(['error' => 'Car is already rented for the selected dates'], 422);
        }

        $rental = Rental::create([
            'car_id' => $request->car_id,
            'user_id' => $request->user_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Atualizar a disponibilidade do carro
        $car->is_available = false;
        $car->save();

        return response()->json($rental, 201);
    }
}
