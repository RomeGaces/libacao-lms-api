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
Route::get('/sections', [ClassSectionController::class, 'index']);
Route::post('/sections', [ClassSectionController::class, 'store']);
Route::get('/sections/{id}', [ClassSectionController::class, 'show']);
Route::put('/sections/{id}', [ClassSectionController::class, 'update']);
Route::delete('/sections/{id}', [ClassSectionController::class, 'destroy']);

// Section-related resources
Route::get('/sections/{id}/schedules', [ClassSectionController::class, 'schedules']);
Route::get('/sections/{id}/students', [ClassSectionController::class, 'students']);
Route::post('/sections/{id}/assign-student', [ClassSectionController::class, 'assignStudent']);
Route::delete('/assignments/{assignmentId}', [ClassSectionController::class, 'removeAssignment']);

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
Route::middleware('auth:sanctum')->apiResource('students', StudentController::class);
Route::middleware('auth:sanctum')->apiResource('student-subject-assignments', StudentSubjectAssignmentController::class);

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
