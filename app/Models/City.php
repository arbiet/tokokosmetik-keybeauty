<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'rajaongkir_cities';

    protected $fillable = [
        'city_id',
        'city_name',
        'type',
        'province_id',
        'postal_code',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }
}
