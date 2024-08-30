<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unauthorized extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural form of the model
    protected $table = 'unauthorized';

    // Disable timestamps if they are not present in the table
    public $timestamps = false;

    // Define the fillable fields
    protected $fillable = [
        'qrcode',
        'plate_no',
        'fullname',    // Add fullname to the fillable fields
        'log_date',
        'time_in',
        'time_out',
    ];
}
