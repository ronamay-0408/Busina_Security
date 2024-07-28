<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $table = 'violation';

    protected $fillable = [
        'plate_no',
        'location',
        'violation_type_id',
        'remarks',
        'proof_image',
        'reported_by',
        'vehicle_id'
    ];

    // Define relationship with ViolationType
    public function violationType()
    {
        return $this->belongsTo(ViolationType::class, 'violation_type_id');
    }

    // Define relationship with Vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    // Define relationship with AuthorizedUser
    public function reportedBy()
    {
        return $this->belongsTo(AuthorizedUser::class, 'reported_by');
    }
}
