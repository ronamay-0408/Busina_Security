<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicle';

    protected $fillable = [
        'vehicle_owner_id',
        'model_color',
        'plate_no',
        'or_cr_no',
        'expiry_date',
        'copy_or_cr',
        'copy_driver_license',
        'copy_cor',
        'copy_school_id',
        'vehicle_type_id',
        'registration_no',
        'claiming_status',
        'vehicle_status',
        'apply_date',
        'issued_date',
        'sticker_expiry'
    ];

    // Define relationship with Violation
    public function violations()
    {
        return $this->hasMany(Violation::class, 'vehicle_id');
    }

    // Define relationship with VehicleOwner
    // public function vehicleOwner()
    // {
        // return $this->belongsTo(VehicleOwner::class, 'vehicle_owner_id');
    // }

    // Define relationship with VehicleType
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }
}
