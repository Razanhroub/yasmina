<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function __construct()
    {
        // Apply policy automatically
        $this->authorizeResource(User::class, 'teacher');
    }

    // /**
    //  * Display a listing of teachers
    //  */
    public function index()
    {
        $teachers = User::role('teacher')->get(['id', 'name']); 
        return response()->json($teachers);
    }


    // /**
    //  * Store a new teacher
    //  */
    // public function store(Request $request)
    // {
    //     $this->authorize('create', User::class);

    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     $teacher = User::create([
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'password' => Hash::make($data['password']),
    //         'role_id' => 2, // teacher role id
    //     ]);

    //     $teacher->assignRole('teacher');

    //     return response()->json($teacher, 201);
    // }

    // /**
    //  * Show a specific teacher
    //  */
    // public function show(User $teacher)
    // {
    //     $teacher->load('classrooms');
    //     return response()->json($teacher);
    // }

    // /**
    //  * Update a teacher
    //  */
    // public function update(Request $request, User $teacher)
    // {
    //     $this->authorize('update', $teacher);

    //     $data = $request->validate([
    //         'name' => 'sometimes|string|max:255',
    //         'email' => 'sometimes|email|unique:users,email,' . $teacher->id,
    //         'password' => 'nullable|string|min:6',
    //     ]);

    //     if (!empty($data['password'])) {
    //         $data['password'] = Hash::make($data['password']);
    //     } else {
    //         unset($data['password']);
    //     }

    //     $teacher->update($data);

    //     return response()->json($teacher);
    // }

    // /**
    //  * Delete a teacher (soft delete)
    //  */
    // public function destroy(User $teacher)
    // {
    //     $this->authorize('delete', $teacher);

    //     // Optional: unassign classrooms or handle cascading logic
    //     foreach ($teacher->classrooms as $classroom) {
    //         $classroom->teacher_id = null;
    //         $classroom->save();
    //     }

    //     $teacher->delete();

    //     return response()->json(['message' => 'Teacher deleted successfully.']);
    // }
}
