<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'stock', 'image', 'category_id', 'slug', 'weight', 'length', 'width', 'height', 'status'];

    // Relasi Many-to-One dengan Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
