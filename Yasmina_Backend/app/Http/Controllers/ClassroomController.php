<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    // public function __construct()
    // {
    //     // Apply policy automatically
    //     $this->authorizeResource(Classroom::class, 'classroom');
    // }

    // /**
    //  * Display a listing of classrooms
    //  */
    // public function index()
    // {
    //     $user = auth()->user();

    //     if ($user->hasRole('admin')) {
    //         $classrooms = Classroom::with('teacher', 'students')->get();
    //     } elseif ($user->hasRole('teacher')) {
    //         $classrooms = Classroom::where('teacher_id', $user->id)
    //                                ->with('students')
    //                                ->get();
    //     } else {
    //         abort(403, 'Unauthorized');
    //     }

    //     return response()->json($classrooms);
    // }

    // /**
    //  * Store a new classroom
    //  */
    // public function store(Request $request)
    // {
    //     $this->authorize('create', Classroom::class);

    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'teacher_id' => 'nullable|exists:users,id',
    //     ]);

    //     // Optional: ensure teacher_id is a teacher
    //     if (isset($data['teacher_id'])) {
    //         $teacher = User::find($data['teacher_id']);
    //         if (!$teacher || !$teacher->hasRole('teacher')) {
    //             return response()->json(['error' => 'Assigned user must be a teacher'], 422);
    //         }
    //     }

    //     $classroom = Classroom::create($data);

    //     return response()->json($classroom, 201);
    // }

    // /**
    //  * Show a specific classroom
    //  */
    // public function show(Classroom $classroom)
    // {
    //     $classroom->load('teacher', 'students');
    //     return response()->json($classroom);
    // }

    // /**
    //  * Update a classroom
    //  */
    // public function update(Request $request, Classroom $classroom)
    // {
    //     $this->authorize('update', $classroom);

    //     $data = $request->validate([
    //         'name' => 'sometimes|string|max:255',
    //         'description' => 'nullable|string',
    //         'teacher_id' => 'nullable|exists:users,id',
    //     ]);

    //     // Optional: ensure teacher_id is a teacher
    //     if (isset($data['teacher_id'])) {
    //         $teacher = User::find($data['teacher_id']);
    //         if (!$teacher || !$teacher->hasRole('teacher')) {
    //             return response()->json(['error' => 'Assigned user must be a teacher'], 422);
    //         }
    //     }

    //     $classroom->update($data);

    //     return response()->json($classroom);
    // }

    // /**
    //  * Delete a classroom (soft delete)
    //  */
    // public function destroy(Classroom $classroom)
    // {
    //     $this->authorize('delete', $classroom);

    //     // Soft delete classroom and optionally its students
    //     foreach ($classroom->students as $student) {
    //         $student->delete();
    //     }

    //     $classroom->delete();

    //     return response()->json(['message' => 'Classroom deleted successfully.']);
    // }
}
