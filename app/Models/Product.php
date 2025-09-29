<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Allow mass assignment for these fields (useful for seeders)
    protected $fillable = ['name', 'price', 'stock'];
}
