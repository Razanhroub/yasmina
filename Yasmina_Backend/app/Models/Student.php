<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'student_id',   // links to the users table
        'class_id',     // links to the classrooms table
        'birth_of_date',
        'country',
    ];
    
    protected $casts = [
    'birth_of_date' => 'date', // or 'date_of_birth' => 'date' if you rename
    ];


    /**
     * Relation: student belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relation: student belongs to a classroom
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}
