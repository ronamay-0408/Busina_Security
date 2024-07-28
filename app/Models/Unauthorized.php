<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unauthorized extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural form of the model
    protected $table = 'unauthorized';

    // Define the fillable fields
    protected $fillable = [
        'plate_no',
        'fullname',
        'purpose',
        'count',
    ];
}
