<?php
namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Http\Requests\ClassroomRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClassroomController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Classroom::class, 'classroom');
    }

    public function index()
    {
        $user = auth()->user();
    //     Log::info('Authenticated user in ClassroomController@index:', [
    //     'user' => $user ? $user->toArray() : null
    // ]);

       if ($user->hasRole('admin')) {
        $classrooms = Classroom::with(['teacher', 'students.user'])->get();
        } elseif ($user->hasRole('teacher')) {
            $classrooms = Classroom::where('teacher_id', $user->id)
                                    ->with(['students.user'])
                                    ->get();
        } else {
            abort(403, 'Unauthorized');
        }

        return response()->json($classrooms, 200);
    }   

    public function store(ClassroomRequest $request)
    {
        $this->authorize('create', Classroom::class);

        $data = $request->validated();

        $classroom = Classroom::create($data);

        return response()->json($classroom, 201);
    }

    
    public function update(ClassroomRequest $request, Classroom $classroom)
    {
        $this->authorize('update', $classroom);

        $data = $request->validated();
       
        $classroom->update($data);

        return response()->json($classroom, 200);
    }

    public function destroy(Classroom $classroom)
    {
        $this->authorize('delete', $classroom);

        foreach ($classroom->students as $student) {
            $student->delete();
        }

        $classroom->teacher_id = null;
        $classroom->save();

        $classroom->delete();

        return response()->json([
            'message' => 'Classroom deleted successfully.'
        ], 200);
    }

     public function available()
    {
        $this->authorize('viewAny', Classroom::class); 

        $classrooms = Classroom::whereNull('teacher_id')->get(['id', 'name']);
        
        return response()->json($classrooms, 200);
    }
}
