<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    // Define the table associated with the model
    protected $table = 'user_logs';

    // Specify the primary key if it is not the default 'id'
    protected $primaryKey = 'id';

    // Define the fields that are mass assignable
    protected $fillable = [
        'vehicle_owner_id',
        'log_date',
        'time_in',
        'time_out',
    ];

    // Disable timestamps if not used (created_at and updated_at fields)
    public $timestamps = false;

    /**
     * Get the vehicle owner that owns the user log.
     */
    public function vehicleOwner()
    {
        return $this->belongsTo(VehicleOwner::class, 'vehicle_owner_id');
    }
}
