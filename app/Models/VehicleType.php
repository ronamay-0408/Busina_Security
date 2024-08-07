<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    // Define the table associated with the model if it's not the plural form of the model name
    protected $table = 'vehicle_type';

    // Specify the primary key if it's not the default 'id'
    protected $primaryKey = 'id';

    // Disable timestamps if your table does not have 'created_at' and 'updated_at' columns
    public $timestamps = false;

    // Define any mass assignable attributes if necessary
    protected $fillable = ['vehicle_type'];
    
    // Define relationships if needed
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'vehicle_type_id');
    }
}
