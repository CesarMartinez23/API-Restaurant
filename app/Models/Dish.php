<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
        'description',
        'price',
        'restaurant_id',
    ];

    static $rules = [
        'name' => 'required|string|min:3',
        'image_path' => 'required|image',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'restaurant_id' => 'required|integer|exists:restaurants,id',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}