<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    // public function __construct()
    // {
    //     // Apply policy for all resource methods
    //     $this->authorizeResource(Student::class, 'student');
    // }

    // /**
    //  * Display a listing of students.
    //  */
    // public function index()
    // {
    //     $user = auth()->user();

    //     // Admin sees all students
    //     if ($user->hasRole('admin')) {
    //         $students = Student::with('user', 'classroom')->get();
    //     }
    //     // Teacher sees only their classroom students
    //     elseif ($user->hasRole('teacher')) {
    //         $students = Student::whereHas('classroom', function ($q) use ($user) {
    //             $q->where('teacher_id', $user->id);
    //         })->with('user', 'classroom')->get();
    //     } else {
    //         abort(403, 'Unauthorized');
    //     }

    //     return response()->json($students);
    // }

    // /**
    //  * Store a new student
    //  */
    // public function store(Request $request)
    // {
    //     $this->authorize('create', Student::class);

    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|string|min:6',
    //         'birth_of_date' => 'nullable|date',
    //         'country' => 'nullable|string|max:255',
    //         'class_id' => 'nullable|exists:classrooms,id',
    //     ]);

    //     // Create user first
    //     $user = User::create([
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'password' => Hash::make($data['password']),
    //         'role_id' => 3, // student
    //     ]);

    //     $user->assignRole('student');

    //     // Create student profile
    //     $student = Student::create([
    //         'student_id' => $user->id,
    //         'class_id' => $data['class_id'] ?? null,
    //         'birth_of_date' => $data['birth_of_date'] ?? null,
    //         'country' => $data['country'] ?? null,
    //     ]);

    //     return response()->json($student, 201);
    // }

    // /**
    //  * Display a specific student
    //  */
    // public function show(Student $student)
    // {
    //     $student->load('user', 'classroom');
    //     return response()->json($student);
    // }

    // /**
    //  * Update a student
    //  */
    // public function update(Request $request, Student $student)
    // {
    //     $this->authorize('update', $student);

    //     $data = $request->validate([
    //         'name' => 'sometimes|string|max:255',
    //         'email' => 'sometimes|email|unique:users,email,' . $student->user->id,
    //         'password' => 'nullable|string|min:6',
    //         'birth_of_date' => 'nullable|date',
    //         'country' => 'nullable|string|max:255',
    //         'role_id' => 'sometimes|exists:roles,id',
    //         'class_id' => 'nullable|exists:classrooms,id',
    //     ]);

    //     // Update user
    //     $student->user->update([
    //         'name' => $data['name'] ?? $student->user->name,
    //         'email' => $data['email'] ?? $student->user->email,
    //         'password' => isset($data['password']) ? Hash::make($data['password']) : $student->user->password,
    //         'role_id' => $data['role_id'] ?? $student->user->role_id,
    //     ]);

    //     // Update student profile
    //     $student->update([
    //         'class_id' => $data['class_id'] ?? $student->class_id,
    //         'birth_of_date' => $data['birth_of_date'] ?? $student->birth_of_date,
    //         'country' => $data['country'] ?? $student->country,
    //     ]);

    //     return response()->json($student);
    // }

    // /**
    //  * Delete a student (soft delete)
    //  */
    // public function destroy(Student $student)
    // {
    //     $this->authorize('delete', $student);

    //     $student->user->delete(); // soft delete user
    //     $student->delete();       // soft delete student profile

    //     return response()->json(['message' => 'Student deleted successfully.']);
    // }
}
