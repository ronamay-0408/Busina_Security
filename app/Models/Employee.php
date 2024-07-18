<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee'; // Specify the correct table name

    protected $fillable = [
        'emp_no', 'fname', 'lname', 'mname', 'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'emp_no', 'emp_no');
    }
}
