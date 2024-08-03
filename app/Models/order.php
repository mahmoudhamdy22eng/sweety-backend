<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'shipping_id',
        'address_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
