<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'model_id',
        'year',
        'color',
        'price_per_day'
    ];

    // Definir a relação com o modelo
    public function model()
    {
        return $this->belongsTo(Modelo::class);
    }

    // Definir a relação com os aluguéis
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
