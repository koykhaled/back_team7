<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CollegeController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TermController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('profile/{id}', [ProfileController::class, 'update']);
});


/*
|--------------------------------------------------------------------------
| Colleges Routes
|--------------------------------------------------------------------------
*/
Route::get('colleges/{id}', [CollegeController::class, 'index'])->name('college.index');
Route::post('colleges/{id}', [CollegeController::class, 'store'])->name('college.store');


/*
|--------------------------------------------------------------------------
| Categories Routes
|--------------------------------------------------------------------------
*/
Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');
/*
|--------------------------------------------------------------------------
| Subjects Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth:sanctum'], function () {
});
Route::get('subjects/{college_id}', [SubjectController::class, 'index'])->name('subject.index');
Route::post('subjects/{id}', [SubjectController::class, 'store'])->name('subject.store');
Route::delete('subjects/{id}', [SubjectController::class, 'destroy'])->name('subject.delete');

Route::get('categories', [CategoryController::class, 'index'])->name('category.index');


/*
|--------------------------------------------------------------------------
| Terms Routes
|--------------------------------------------------------------------------
*/
Route::get('terms/{college_id}', [TermController::class, 'index'])->name('subject.index');
Route::post('terms/{college_id}', [TermController::class, 'store'])->name('subject.store');
Route::delete('terms/{id}', [TermController::class, 'destroy'])->name('subject.delete');



/*
|--------------------------------------------------------------------------
| Questions Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('questions/', [QuestionController::class, 'index'])->name('questions.index');
});
Route::post('questions/{subject_id}', [QuestionController::class, 'store'])->name('questions.store');
Route::delete('questions/{id}', [QuestionController::class, 'destroy'])->name('questions.delete');