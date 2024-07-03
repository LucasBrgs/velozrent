<?php

namespace App\Http\Controllers;

use App\Models\Modelo; // Alterado para evitar conflito com o namespace PHP
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModelController extends Controller
{
    public function store(Request $request)
    {
        // Definir as regras de validação
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
        ]);

        // Se a validação falhar, retornar a resposta com os erros
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Criar um novo modelo
        $model = Modelo::create([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
        ]);

        // Retornar a resposta de sucesso
        return response()->json($model, 201);
    }
}
