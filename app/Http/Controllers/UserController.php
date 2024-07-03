<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    /**
     * Get rentals of a specific user.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function getUserRentals($user_id)
    {
        $user = User::findOrFail($user_id);

        // Carregar os aluguéis do usuário
        $rentals = $user->rentals()->with('car')->get();

        return response()->json($rentals);
    }
}
