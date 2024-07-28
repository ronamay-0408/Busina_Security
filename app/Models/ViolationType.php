<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    use HasFactory;

    protected $table = 'violation_type';

    protected $fillable = [
        'violation_name',
        'penalty_fee'
    ];

    // Define relationship with Violation
    public function violations()
    {
        return $this->hasMany(Violation::class, 'violation_type_id');
    }
}

