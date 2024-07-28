<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_no',
        'users_id',
        'token',
        'expiration',
        'used_reset_token',
    ];

    protected $dates = [
        'expiration',
    ];

    // Define any relationships if needed
    public function user()
    {
        return $this->belongsTo(Users::class);
    }
}
