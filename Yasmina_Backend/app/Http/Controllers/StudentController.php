<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\UpdateStudentProfileRequest;
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
    

    
    // Assign Teacher role
    public function toggleRole(User $user)
    {
        
        $user->syncRoles([]);

        $user->assignRole('teacher');

        return response()->json([
            'status' => 200,
            'message' => "$user->name is now a Teacher"
        ]);
    }


    public function destroyByUser($userId)
        {
            $user = User::findOrFail($userId);
            $student = $user->student;     
            $student->delete();
            $user->delete();

            return response()->json([
            'status' => 200,
            'message' => 'Student deleted successfully'
        ]);
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

    public function updateProfile(UpdateStudentProfileRequest $request)
        {
            $student = $request->user()->student;

            if (!$student) {
                return response()->json(['message' => 'Student profile not found'], 404);
            }

            $this->authorize('update', $student); // Keep policy check

            $data = $request->validated();

            // Hash password if provided
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $userData = array_intersect_key($data, ['name' => '', 'email' => '', 'password' => '']);
            $studentData = array_diff_key($data, $userData);

            // Update
            if (!empty($userData)) {
                $student->user->update($userData);
            }
            if (!empty($studentData)) {
                $student->update($studentData);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Profile updated successfully',
                'data' => $student->load('user', 'classroom.teacher')
            ]);
        }

    public function assignClassroom(Request $request, $studentId)
        {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|exists:classrooms,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            try {
                // Find the student record by student_id (which references users table)
                $student = Student::where('student_id', $studentId)->first();

                if (!$student) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Student not found'
                    ], 404);
                }

                // Update the classroom assignment
                $student->class_id = $request->class_id;
                $student->save();

                // Load relationships for response
                $student->load(['user', 'classroom']);

                return response()->json([
                    'success' => true,
                    'message' => 'Student assigned to classroom successfully',
                    'data' => $student
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to assign classroom',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
}
