<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'rajaongkir_provinces';

    protected $fillable = [
        'province_id',
        'province_name',
    ];

    public function cities()
    {
        return $this->hasMany(City::class, 'province_id', 'province_id');
    }
}
