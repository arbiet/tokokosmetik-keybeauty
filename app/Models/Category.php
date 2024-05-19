<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relasi One-to-Many dengan Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Method untuk menghapus produk terkait saat kategori dihapus
    public static function boot()
    {
        parent::boot();

        static::deleting(function($category) {
            $category->products()->delete();
        });
    }
}

