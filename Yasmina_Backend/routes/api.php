    <?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\Auth\LoginController;

    
    

    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);

    // routes/api.php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

Route::middleware(['auth:sanctum', 'role:admin'])
        ->prefix('admin')
        ->group(function () {
    // Classrooms
    Route::get('classrooms', [ClassroomController::class, 'index']);
    Route::post('classrooms', [ClassroomController::class, 'store']);
    Route::put('classrooms/{classroom}', [ClassroomController::class, 'update']);
    Route::delete('classrooms/{classroom}', [ClassroomController::class, 'destroy']);
    Route::get('classrooms/available', [ClassroomController::class, 'available']);

    // Students
    Route::get('students', [StudentController::class, 'index']);
    Route::post('students', [StudentController::class, 'store']);
    Route::delete('students/user/{userId}', [StudentController::class, 'destroyByUser']);
    Route::put('users/{user}/toggle-role', [StudentController::class, 'toggleRole']);


    // Teachers
    Route::get('teachers', [TeacherController::class, 'index']);
    Route::get('teachers/{teacher}', [TeacherController::class, 'show']);
    Route::post('teachers', [TeacherController::class, 'store']);
    Route::put('teachers/{teacher}', [TeacherController::class, 'update']);
    Route::delete('teachers/{teacher}', [TeacherController::class, 'destroy']);
});
Route::middleware(['auth:sanctum', 'role:student'])
    ->prefix('student')
    ->group(function () {

        Route::get('profile', [StudentController::class, 'profile']);
        Route::put('profile', [StudentController::class, 'updateProfile']);

    });



 
