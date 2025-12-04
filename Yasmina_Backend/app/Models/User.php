<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;
    //used factory for the seeder

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',//connected with spatie role id
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * Relation: the role assigned via spatie
     */
    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Relation: if user is a teacher, has many classrooms
     */
    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }
     /**
     * Relation: if user is a student, has one student profile
     */
    public function studentProfile()
    {
        return $this->hasOne(Student::class, 'student_id');
    }
    /**
     * Relation: teacher can get all students through their classrooms
     */
    public function students()
    {
        return $this->hasManyThrough(
            Student::class,
            Classroom::class,
            'teacher_id',  // Foreign key on Classroom table
            'class_id',    // Foreign key on Student table
            'id',          // Local key on User table
            'id'           // Local key on Classroom table
        );
    }
}
