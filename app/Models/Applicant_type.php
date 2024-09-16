<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant_type extends Model
{
    use HasFactory;

    protected $table = 'applicant_type';

    protected $fillable = ['type'];

    public function vehicle_owner() {
        return $this->hasMany(VehicleOwner::class, 'applicant_type_id');
    }
}
