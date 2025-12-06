<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens; 

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        
        
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function student()
    {
        return $this->hasOne(Student::class, 'student_id'); // links to student_id in students table
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function classroomForStudent()
    {
        return $this->hasOneThrough(
            Classroom::class,
            Student::class,
            'student_id', // Foreign key on Student table...
            'id',         // Foreign key on Classroom table...
            'id',         // Local key on User table
            'class_id'    // Local key on Student table
        );
    }
    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'teacher_id'); // teacher -> classrooms
    }
}