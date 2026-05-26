<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldPin extends Model
{
    use HasFactory;

    protected $table = 'old_pins';

    protected $fillable = [
        'user_id',
        'pin'
    ];
}