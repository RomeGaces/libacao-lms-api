<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ClassSectionController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentSubjectAssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;



use App\Http\Controllers\InstructorsController;
use App\Http\Controllers\SectionController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::post('/createToken', [LoginController::class, 'createToken']);


Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->apiResource('departments', DepartmentController::class);

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::post('/courses', [CourseController::class, 'store']);
Route::put('/courses/{id}', [CourseController::class, 'update']);
Route::delete('/courses/{id}', [CourseController::class, 'destroy']);


// course-scoped (optional convenience)
Route::get('/courses/{courseId}/subjects', [SubjectController::class, 'getByCourse']);
Route::post('/courses/{courseId}/subjects', function (Request $r, $courseId, SubjectController $ctrl) {
    // delegate to the new controller so old course-controller routes keep working
    $data = array_merge($r->all(), ['course_id' => $courseId]);
    // create a new request with course_id then call store()
    $newReq = $r->duplicate($data);
    return $ctrl->store($newReq);
});



Route::get('/subjects', [SubjectController::class, 'index']);
Route::get('/subjects/{id}', [SubjectController::class, 'show']);
Route::post('/subjects', [SubjectController::class, 'store']);
Route::put('/subjects/{id}', [SubjectController::class, 'update']);
Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']);

Route::middleware('auth:sanctum')->apiResource('professors', ProfessorController::class);
Route::middleware('auth:sanctum')->apiResource('rooms', RoomController::class);

Route::middleware('auth:sanctum')->prefix('sections')->group(function () {

    Route::get('/', [ClassSectionController::class, 'index']);
    Route::post('/', [ClassSectionController::class, 'store']);
    Route::get('/{id}', [ClassSectionController::class, 'show']);
    Route::put('/{id}', [ClassSectionController::class, 'update']);
    Route::delete('/{id}', [ClassSectionController::class, 'destroy']);

    // schedules
    Route::get('/{id}/schedules', [ClassSectionController::class, 'schedules']);
    Route::get('/{id}/validate-schedules', [ClassSectionController::class, 'validateSectionSchedule']);
    Route::get('/{id}/conflicts', [ClassSectionController::class, 'detectConflicts']);
    //Route::get('/sections/{id}/available-students', [ClassSectionController::class, 'availableStudents']);
    Route::get('/{id}/available-students', [ClassSectionController::class, 'availableStudents']);

    // students
    Route::get('/{id}/students', [ClassSectionController::class, 'students']);
    Route::post('/{id}/assign-student', [ClassSectionController::class, 'assignStudent']);
    Route::post('/{id}/auto-assign', [ClassSectionController::class, 'autoAssign']);

    // lock/unlock
    Route::post('/{id}/lock', [ClassSectionController::class, 'lock']);
    Route::post('/{id}/unlock', [ClassSectionController::class, 'unlock']);

    // timetable
    Route::get('/{id}/timetable', [ClassSectionController::class, 'timetable']);
});

// remove assignment globally
Route::middleware('auth:sanctum')->delete('/assignments/{assignmentId}', [ClassSectionController::class, 'removeAssignment']);

Route::middleware('auth:sanctum')->get('schedules/conflicts', [ClassScheduleController::class, 'checkConflicts']);
Route::middleware('auth:sanctum')->get('schedules/query', [ClassScheduleController::class, 'index']);
Route::middleware('auth:sanctum')->post('schedules/check-conflict', [ClassScheduleController::class, 'checkConflict']);
Route::middleware('auth:sanctum')->get('schedules/timeslot/{day_of_week}/{start_hour}', [ClassScheduleController::class, 'getByTimeslot']);
Route::middleware('auth:sanctum')->apiResource('schedules', ClassScheduleController::class);
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('schedules', ClassScheduleController::class);
//     Route::get('schedules/conflicts', [ClassScheduleController::class, 'checkConflicts']);
//     Route::get('schedules/query', [ClassScheduleController::class, 'getQuery']); 
//     Route::post('schedules/check-conflict', [ClassScheduleController::class, 'checkConflict']);
//     Route::get('schedules/timeslot/{day_of_week}/{start_hour}', [ClassScheduleController::class, 'getByTimeslot']);
// });
Route::middleware('auth:sanctum')->prefix('students')->group(function () {

    // STATIC ROUTES FIRST
    Route::get('/next-number', [StudentController::class, 'nextNumber']);
    Route::get('/check-email', [StudentController::class, 'checkEmail']);

    // RESOURCE ROUTES
    Route::get('/', [StudentController::class, 'index']);
    Route::post('/', [StudentController::class, 'store']);

    // DYNAMIC ROUTES LAST
    Route::get('/{id}/schedule', [StudentController::class, 'schedule']);
    Route::get('/{id}/transcript', [StudentController::class, 'transcript']);
    Route::put('/{id}', [StudentController::class, 'update']);
    Route::delete('/{id}', [StudentController::class, 'destroy']);
    Route::get('/{id}', [StudentController::class, 'show']);   // ALWAYS LAST
});
Route::middleware('auth:sanctum')->apiResource('student-subject-assignments', StudentSubjectAssignmentController::class);
Route::middleware(['auth:sanctum'])->get(
    '/dashboard/ay-summary',
    [AdminController::class, 'summary']
);
Route::middleware(['auth:sanctum'])->post('/master-setup',
    [AdminController::class, 'dryRun']
);
// Route::middleware('api')->group(function () {
//     Route::apiResource('instructors', InstructorsController::class);
// });



// //PAO Request
// Route::prefix('pao-requests')->middleware('auth:sanctum')->group(function () {
//     Route::post('/', [PaoRequestController::class, 'store']);
//     Route::get('/', [PaoRequestController::class, 'index']);
//     Route::get('/{id}', [PaoRequestController::class, 'show']);
//     Route::patch('/{id}', [PaoRequestController::class, 'update']);
//     Route::delete('/{id}', [PaoRequestController::class, 'destroy']); // delete full request
//     Route::delete('/{requestId}/objects/{objectId}', [PaoRequestController::class, 'destroyObject']); // delete single object
//     Route::delete('/{requestId}/groups/{groupId}', [PaoRequestController::class, 'destroyGroup']);
// });
