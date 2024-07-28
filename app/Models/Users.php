<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // Ensure this matches your table name

    protected $fillable = [
        'authorized_user_id', 'vehicle_owner_id', 'email', 'password', 'created_at', 'updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function authorizedUser()
    {
        return $this->belongsTo(AuthorizedUser::class, 'authorized_user_id');
    }

    public function vehicleOwner()
    {
        return $this->belongsTo(VehicleOwner::class, 'vehicle_owner_id');
    }
}
