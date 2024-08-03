<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsLog extends Model
{
    use HasFactory;

    protected $fillable = ['action', 'action_type'];
}
