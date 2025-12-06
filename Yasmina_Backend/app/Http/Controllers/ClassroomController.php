<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use App\Http\Requests\ClassroomRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;


class ClassroomController extends Controller
{
    public function __construct()
    {
        // Apply policy automatically
        $this->authorizeResource(Classroom::class, 'classroom');
    }

/**
 * Display a listing of classrooms
 */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $classrooms = Classroom::with('teacher', 'students')->get();
        } elseif ($user->hasRole('teacher')) {
            $classrooms = Classroom::where('teacher_id', $user->id)
                                ->with('students')
                                ->get();
        } else {
            abort(403, 'Unauthorized');
        }

        return response()->json($classrooms);
    }

/**
 * Store a new classroom
 */
    
    public function store(ClassroomRequest $request)
    {
        $this->authorize('create', Classroom::class);

        // All validated data is automatically returned by the request
        $data = $request->validated();

        $classroom = Classroom::create($data);

        return response()->json($classroom, 201);
    }

    /**
     * Update a classroom
     */
    public function update(ClassroomRequest $request, Classroom $classroom)
    {
        $this->authorize('update', $classroom);

        // Use validated data from the request
        $data = $request->validated();
       
        $classroom->update($data);

        return response()->json($classroom);
    }

    // /**
    // /**
    //  * Delete a classroom (soft delete)
    //  */
        public function destroy(Classroom $classroom)
    {
        $this->authorize('delete', $classroom);

        // Soft delete all students in this classroom
        foreach ($classroom->students as $student) {
            $student->delete();
        }

        // Unassign teacher
        $classroom->teacher_id = null; // or 0 if your schema prefers
        $classroom->save();

        // Soft delete classroom
        $classroom->delete();

        return response()->json([
            'message' => 'Classroom deleted successfully.'
        ]);
    }
}
