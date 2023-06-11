<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
        'description',
        'address',
        'phone',
        'category_id',
    ];

    static $rules = [
        'name' => 'required|string|min:3',
        'image_path' => 'required|image',
        'description' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'phone' => 'required|string|min:8',
        'category_id' => 'required|integer|exists:categories,id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}