<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassroomPolicy
{
    use HandlesAuthorization;

    /**
     * Before method: admin can do everything
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true; // admin can manage all classrooms
        }
    }

    /**
     * Determine whether the user can view any classrooms
     */
    public function viewAny(User $user)
    {
        if ($user->hasRole('teacher')) {
            return true; // teacher can view their classrooms (filter in controller)
        }

        return false; // students cannot list classrooms
    }

    /**
     * Determine whether the user can view a specific classroom
     */
    public function view(User $user, Classroom $classroom)
    {
        if ($user->hasRole('teacher')) {
            // teacher can view only classrooms they are assigned to
            return $classroom->teacher_id === $user->id;
        }

        if ($user->hasRole('student')) {
            // student can view their own classroom
            return $user->students && $user->students->class_id === $classroom->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create classrooms
     */
    public function create(User $user)
    {
        return false; // only admin can create, handled in before()
    }

    /**
     * Determine whether the user can update classrooms
     */
    public function update(User $user, Classroom $classroom)
    {
        if ($user->hasRole('teacher')) {
            return $classroom->teacher_id === $user->id; // teacher can update only own classrooms
        }

        return false;
    }

    /**
     * Determine whether the user can delete classrooms
     */
    public function delete(User $user, Classroom $classroom)
    {
        if ($user->hasRole('teacher')) {
            return $classroom->teacher_id === $user->id; // teacher can delete only own classrooms
        }

        return false;
    }
}
