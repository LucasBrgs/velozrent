<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{

    /**
     * List all available cars or filter by parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Capturar os parâmetros de filtro
        $query = Car::query();

        if ($request->has('license_plate')) {
            $query->where('license_plate', 'like', '%' . $request->license_plate . '%');
        }

        if ($request->has('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        if ($request->has('brand_id')) {
            $query->whereHas('model', function ($query) use ($request) {
                $query->where('brand_id', $request->brand_id);
            });
        }

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        if ($request->has('color')) {
            $query->where('color', 'like', '%' . $request->color . '%');
        }

        if ($request->has('price_min')) {
            $query->where('price_per_day', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('price_per_day', '<=', $request->price_max);
        }

        if ($request->has('is_available')) {
            $query->where('is_available', $request->is_available);
        }

        // Incluir informações de aluguel se o carro não estiver disponível
        $cars = $query->with(['rentals.user'])->get();

         // Formatando a resposta para incluir informações de aluguel se não estiver disponível
         $formattedCars = $cars->map(function ($car) {
            $formattedCar = $car->toArray();

            if (!$car->is_available) {
                $rental = $car->rentals->last(); // Obtém o último aluguel (assumindo o mais recente)
                $formattedCar['rented_by'] = [
                    'user_id' => $rental->user_id,
                    'user_name' => $rental->user->name,
                    'start_date' => $rental->start_date,
                    'end_date' => $rental->end_date,
                ];
            }

            // Formatando as datas created_at e updated_at
            $formattedCar['created_at'] = $car->created_at->format('d/m/Y H:i:s');
            $formattedCar['updated_at'] = $car->updated_at->format('d/m/Y H:i:s');

            return $formattedCar;
        });

        // Retornar os resultados
        return response()->json($formattedCars);
    }

    public function store(Request $request)
    {
        // Definir as regras de validação
        $validator = Validator::make($request->all(), [
            'license_plate' => 'required|string|unique:cars,license_plate|max:10',
            'model_id' => 'required|exists:models,id',
            'year' => 'required|integer|digits:4|min:1900|max:' . date('Y'),
            'color' => 'required|string|max:20',
            'price_per_day' => 'required|numeric|min:0'
        ]);

        // Se a validação falhar, retornar a resposta com os erros
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Criar um novo carro
        $car = Car::create([
            'license_plate' => $request->license_plate,
            'model_id' => $request->model_id,
            'year' => $request->year,
            'color' => $request->color,
            'price_per_day' => $request->price_per_day
        ]);

        // Retornar a resposta de sucesso
        return response()->json($car, 201);
    }
}
