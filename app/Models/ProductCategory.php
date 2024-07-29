<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ['CategoryName', 'AdminID', 'CategoryType'];

    protected $table = 'productcategories';

    protected $primaryKey = 'CategoryID';

    public function products()
    {
        return $this->hasMany(Product::class, 'CategoryID', 'CategoryID');
    }
}