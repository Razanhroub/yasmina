<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

       /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'name',
        'description',
        'teacher_id',
    ];
     /**
     * Relation: classroom belongs to a teacher (user with role_id = 2)
     */
    public function teacher()
    {
       return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relation: classroom has many students
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
   
}
