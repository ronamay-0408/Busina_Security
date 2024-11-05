<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AuthorizedUser extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'authorized_user'; // Ensure this matches your table name

    protected $fillable = [
        'fname', 'lname', 'mname', 'contact_no', 'user_type_id', 'emp_id',
    ];

    // Optionally specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Accessor to get the full name
    public function getFullNameAttribute()
    {
        return trim("{$this->fname} {$this->mname} {$this->lname}");
    }

    // Define relationship with Violation
    public function violations()
    {
        return $this->hasMany(Violation::class, 'reported_by');
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->hasOne(Users::class, 'authorized_user_id');
    }

    // Define the relationship to the Employee model using emp_id
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'emp_no');
    }

    // Optionally, you can create an accessor for emp_no
    public function getEmpNoAttribute()
    {
        return $this->employee ? $this->employee->emp_no : null;
    }
}
