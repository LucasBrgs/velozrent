<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    public function store(Request $request)
    {
        // Definir as regras de validação
        $validator = Validator::make($request->all(), [
            'path' => 'required|string|max:255',
            'car_id' => 'required|exists:cars,id',
        ]);

        // Se a validação falhar, retornar a resposta com os erros
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Criar uma nova foto
        $photo = Photo::create([
            'path' => $request->path,
            'car_id' => $request->car_id,
        ]);

        // Retornar a resposta de sucesso
        return response()->json($photo, 201);
    }
}
