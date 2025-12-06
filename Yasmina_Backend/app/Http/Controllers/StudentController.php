<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Http\Requests\StudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;   
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class StudentController extends Controller
{
    public function __construct()
    {
        // Apply policy for all resource methods
        $this->authorizeResource(Student::class, 'student');
    }

    /**
     * Display a listing of students.
     */
    public function index()
{
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        $students = User::role('student')
            ->with(['student.classroom'])
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'Students retrieved successfully',
            'data' => $students
        ], 200);
    }

    if ($user->hasRole('teacher')) {
        $students = User::role('student')
            ->whereHas('student.classroom', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->with(['student.classroom'])
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'Students retrieved successfully',
            'data' => $students
        ], 200);
    }

    return response()->json([
        'status' => 403,
        'message' => 'Unauthorized'
    ], 403);
}


    /**
     * Store a new student
     */
    public function store(StudentRequest $request)
    {
        $this->authorize('create', Student::class);

        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('student');

        $student = Student::create([
            'student_id' => $user->id,
            'class_id' => $data['class_id'] ?? null,
            'birth_of_date' => $data['birth_of_date'] ?? null,
            'country' => $data['country'] ?? null,
        ]);

        return response()->json($student, 201);
    }


    public function toggleRole(User $user)
    {
        $this->authorize('update', $user);
        $user->syncRoles([]);

        // Assign Teacher role
        $user->assignRole('teacher');

        return response()->json([
            'status' => 200,
            'message' => "$user->name is now a Teacher"
        ]);
    }




    /**
     * Delete a student (soft delete)
        */
    public function destroyByUser($userId)
    {
        $user = User::findOrFail($userId);
        $student = $user->student;     
        $student->delete();
        $user->delete();

        return response()->json(['message' => 'Student deleted successfully']);
    }

    public function profile(Request $request)
{
    $user = $request->user();

    // Ensure the user has a student record
    if (!$user->student) {
        // Create a student record if not exists
        $student = $user->student()->create([
            'birth_of_date' => null,
            'country' => null,
            'class_id' => null,
        ]);
    } else {
        $student = $user->student;
    }

    // Load related user and classroom with teacher
    $student->load([
        'user',
        'classroom.teacher' // load classroom and its teacher
    ]);

    return response()->json([
        'status' => 200,
        'message' => 'Profile retrieved successfully',
        'data' => $student
    ]);
}




    public function updateProfile(Request $request)
    {
        //fixing the issue of student profile update
        $student = $request->user()->student;

        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }

        $this->authorize('update', $student); // Use policy

        $userUpdate = [];
        $studentUpdate = [];
        $errors = [];

        // Name validation
        if ($request->has('name') && $request->name !== $student->user->name) {
            if (strlen($request->name) < 5) {
                $errors['name'][] = 'Name must be at least 5 characters';
            } else {
                $userUpdate['name'] = $request->name;
            }
        }

        // Email validation (only if changed)
        if ($request->has('email') && $request->email !== $student->user->email) {
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'][] = 'Email is not valid';
            } elseif (\App\Models\User::where('email', $request->email)
                ->where('id', '!=', $student->user->id)
                ->exists()) {
                $errors['email'][] = 'Email is already taken';
            } else {
                $userUpdate['email'] = $request->email;
            }
        }

        // Password validation (optional)
        if ($request->filled('password')) {
            $password = $request->password;
            $password_confirmation = $request->password_confirmation;

            if (strlen($password) < 8) {
                $errors['password'][] = 'Password must be at least 8 characters';
            }
            if (!preg_match('/[a-z]/', $password)) {
                $errors['password'][] = 'Password must contain at least one lowercase letter';
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $errors['password'][] = 'Password must contain at least one uppercase letter';
            }
            if (!preg_match('/[0-9]/', $password)) {
                $errors['password'][] = 'Password must contain at least one number';
            }
            if (!preg_match('/[@$!%*#?&]/', $password)) {
                $errors['password'][] = 'Password must contain at least one special character';
            }
            if ($password !== $password_confirmation) {
                $errors['password_confirmation'][] = 'Password confirmation does not match';
            }

            if (!isset($errors['password']) && !isset($errors['password_confirmation'])) {
                $userUpdate['password'] = Hash::make($password);
            }
        }

        // Birth date and country
        if ($request->has('birth_of_date') && $request->birth_of_date !== $student->birth_of_date) {
            $studentUpdate['birth_of_date'] = $request->birth_of_date;
        }

        if ($request->has('country') && $request->country !== $student->country) {
            $studentUpdate['country'] = $request->country;
        }

        // Class ID
        if ($request->has('class_id') && $request->class_id != $student->class_id) {
            $studentUpdate['class_id'] = $request->class_id;
        }

        // Return errors if any
        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        // Apply updates
        if (!empty($userUpdate)) {
            $student->user->update($userUpdate);
        }

        if (!empty($studentUpdate)) {
            $student->update($studentUpdate);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Profile updated successfully',
            'data' => $student->load('user', 'classroom.teacher')
        ]);
    }

      public function assignClassroom(Request $request, $userId)
{
    $student = Student::where('student_id', $userId)->firstOrFail();
        $this->authorize('update', $student); // policy check
        
        $request->validate([
            'class_id' => 'required|exists:classrooms,id',
        ]);

        $classroom = Classroom::findOrFail($request->class_id);

        // Assign student to classroom
        $student->class_id = $classroom->id;
        $student->save();

        return response()->json([
            'status' => 200,
            'message' => "Student assigned to classroom {$classroom->name} successfully",
            'data' => [
                'student_id' => $student->id,
                'classroom_id' => $classroom->id,
                'classroom_name' => $classroom->name
            ]
        ]);
    }


}
