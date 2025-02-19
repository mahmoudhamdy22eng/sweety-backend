<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price',  'QuantityAvailable', 'CategoryID', 'AdminID',
        'IsCustomizable', 'HasNutritionalInfo', 'image', 'vendor', 'is_deleted'
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'CategoryID', 'CategoryID');
    }
}
