<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'admin';
    protected $fillable = [
        'name', 'email', 'role', 'is_deleted', 'password',
    ];

    // Example relationship: an admin can have many users
    public function users()
    {
        return $this->hasMany(User::class);
    }
}