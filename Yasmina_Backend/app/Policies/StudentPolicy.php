<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    /**
     * Before method: admin can do anything
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true; // admin can manage everything
        }
    }

    /**
     * Determine whether the user can view any students
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('teacher');

        return false; // students cannot view other students
    }

    /**
     * Determine whether the user can view a student
     */
    public function view(User $user, Student $student)
    {
        if ($user->hasRole('teacher')) {
            // teacher can only view students in their classrooms
            return $student->classroom && $student->classroom->teacher_id === $user->id;
        }

        if ($user->hasRole('student')) {
            // student can view their own profile and their classroom
            return $student->student_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create a student
     */
    public function create(User $user)
    {
        if ($user->hasRole('teacher')) {
            return true; // teacher can create students in their classrooms
        }

        return false; // students cannot create students, admin handled in before()
    }

    /**
     * Determine whether the user can update the student
     */
    public function update(User $user, Student $student)
    {
        if ($user->hasRole('teacher')) {
            // teacher can update students only in their classrooms
            return $student->classroom && $student->classroom->teacher_id === $user->id;
        }

        if ($user->hasRole('student')) {
            // student can update their own profile
            return $student->student_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the student
     */
    public function delete(User $user, Student $student)
    {
        if ($user->hasRole('teacher')) {
            // teacher can delete students only in their classrooms
            return $student->classroom && $student->classroom->teacher_id === $user->id;
        }

        return false; // students cannot delete themselves, admin handled in before()
    }
}
