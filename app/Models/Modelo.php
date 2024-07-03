<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $table = 'models';

    protected $fillable = [
        'name',
        'brand_id'
    ];

    // Definir a relação com a marca
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
