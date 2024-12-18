<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleOwner extends Model
{
    use HasFactory;

    protected $table = 'vehicle_owner';

    // protected $fillable = [
    //     'fname',
    //     'lname',
    //     'mname',
    //     'contact_no',
    //     'applicant_type_id',
    //     'qr_code',
    //     'emp_id',
    //     'std_id',
    //     'driver_license_no'
    // ];

    protected $fillable = [
        'fname',
        'lname',
        'mname',
        'contact_no',
        'applicant_type_id',
        'qr_code',
        'id_no', // Since it's now in the table
        'driver_license_no'
    ];
    

    // Define relationships

    public function applicantType()
    {
        return $this->belongsTo(Applicant_type::class, 'applicant_type_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }

    // public function student()
    // {
    //     return $this->belongsTo(Student::class, 'std_id');
    // }

    public function userLogs()
    {
        return $this->hasMany(UserLog::class, 'vehicle_owner_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'vehicle_owner_id');
    }

}
