<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function store(Request $request)
    {
        // Definir as regras de validação
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name',
        ]);

        // Se a validação falhar, retornar a resposta com os erros
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Criar uma nova marca
        $brand = Brand::create([
            'name' => $request->name,
        ]);

        // Retornar a resposta de sucesso
        return response()->json($brand, 201);
    }
}
