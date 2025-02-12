<?php

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAssigned;
use App\Http\Controllers\XMLContentController;
Route::get('/test-task-assigned', function () {
    $task = [
        'description' => 'Design mockups',
        'start_date' => '2023-12-01',
        'end_date' => '2023-12-10',
    ];
    $employee = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'recipient_email@example.com', // Replace with recipient's email
    ];

    Mail::to($employee['email'])->send(new TaskAssigned($task, $employee));

    return response()->json(['message' => 'TaskAssigned email sent successfully!']);
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Remove the Sanctum middleware if you're not using it
Route::get('/user', function (Request $request) {
    return $request->user();
});

// Resourceful routes for CRUD operations in API (excluding CSRF protection)
Route::resource('admins', AdminController::class);
Route::resource('projects', ProjectController::class);
Route::resource('tasks', TaskController::class);
Route::resource('task-assignments', TaskAssignmentController::class);
Route::resource('employees', EmployeeController::class);
Route::resource('equipment', EquipmentController::class);
Route::resource('certifications', CertificationController::class);
Route::resource('reports', ReportController::class);
Route::delete('tasks', [TaskController::class, 'deleteTasksByProject']);


Route::get('/api/xml/{entity}', [XMLContentController::class, 'getXMLContent']);

// Login route
Route::post('/admin/login', [AuthController::class, 'login']);
Route::get('/certification/{id}', [CertificationController::class, 'getCertificationsByEmployee']);
// Protect routes with Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/logout', [AuthController::class, 'logout']);
    // Add any other protected routes here
});
Route::post('/upload-image', [ImageController::class, 'upload']);
Route::get('/image/{filename}', [ImageController::class, 'getImage']);
Route::middleware('auth:sanctum')->get('/me', [AdminController::class, 'me']);


