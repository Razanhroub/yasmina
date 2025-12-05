<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeacherPolicy
{
    use HandlesAuthorization;

    /**
     * Before method: admin can do everything
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true; // admin can manage all teachers
        }
    }

    /**
     * Determine whether the user can view any teachers
     */
    public function viewAny(User $user)
    {
        return false; // only admin can view teachers (handled in before)
    }

    /**
     * Determine whether the user can view a specific teacher
     */
    public function view(User $user, User $teacher)
    {
        return false; // only admin can view teachers (handled in before)
    }

    /**
     * Determine whether the user can create teachers
     */
    public function create(User $user)
    {
        return false; // only admin can create teachers (handled in before)
    }

    /**
     * Determine whether the user can update teachers
     */
    public function update(User $user, User $teacher)
    {
        return false; // only admin can update teachers (handled in before)
    }

    /**
     * Determine whether the user can delete teachers
     */
    public function delete(User $user, User $teacher)
    {
        return false; // only admin can delete teachers (handled in before)
    }
}
