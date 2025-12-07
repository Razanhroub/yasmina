<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function __construct()
    {
        // Apply policy automatically
        $this->authorizeResource(User::class, 'teacher');
    }

    public function index()
    {
        $teachers = User::role('teacher')
            ->with('classrooms:id,name,teacher_id')
            ->get(['id', 'name', 'email']); 

        return response()->json($teachers, 200);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'class_names' => 'nullable|array', 
            'class_names.*' => 'string|max:255',
        ]);

        // Create teacher user
        $teacher = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $teacher->assignRole('teacher');

        // Create new classrooms for this teacher
        if (!empty($data['class_names'])) {
            foreach ($data['class_names'] as $className) {
                Classroom::create([
                    'name' => $className,
                    'teacher_id' => $teacher->id,
                ]);
            }
        }

        return response()->json($teacher->load('classrooms'), 201);
    }


    public function update(Request $request, User $teacher)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $teacher->id,
            'password' => 'nullable|string|min:6|confirmed', 
            'class_id' => 'nullable|exists:classrooms,id', 
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $teacher->update($data);

        // Assign new class if provided
        if (!empty($data['class_id'])) {
            // Ensure the classroom is unassigned
            $classroom = Classroom::where('id', $data['class_id'])
                ->whereNull('teacher_id')
                ->first();

            if ($classroom) {
                $classroom->teacher_id = $teacher->id;
                $classroom->save();
            }
        }

         return response()->json(
        $teacher->load('classrooms'),
        200 
    );
    }


    public function destroy(User $teacher)
    {
        // Unassign all classrooms assigned to this teacher
        foreach ($teacher->classrooms as $classroom) {
            $classroom->teacher_id = null;
            $classroom->save();
        }
        $teacher->delete();

        return response()->json(
        ['message' => 'Teacher deleted successfully.'],
        200 
    );
    }

}
