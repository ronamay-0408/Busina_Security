<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'transaction';

    // Define the primary key if it's not the default 'id'
    protected $primaryKey = 'id';

    // Specify if the primary key is auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Disable timestamps if not used
    public $timestamps = true;

    // Specify which attributes are mass assignable
    protected $fillable = [
        'vehicle_id',
        'reference_no',
        'registration_no',
        'emp_id',
        'claiming_status_id',
        'apply_date',
        'issued_date',
        'vehicle_status',
        'sticker_expiry',
        'amount_payable',
        'transac_type'
    ];

    // If you need to cast attributes to specific types
    protected $casts = [
        'apply_date' => 'date',
        'issued_date' => 'date',
        'sticker_expiry' => 'date',
        'amount_payable' => 'decimal:2',
    ];

    // Define any relationships with other models if applicable
    // For example, if there's a relationship with the Vehicle model
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
