<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'user_id',
        'start_date',
        'end_date'
    ];

    // Definir a relação com o carro
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // Definir a relação com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
