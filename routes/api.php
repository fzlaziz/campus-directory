<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\StudyProgramController;
use App\Http\Controllers\DegreeLevelController;
use App\Http\Controllers\AccreditationController;
use App\Http\Controllers\CampusTypeController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NewsCommentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\CampusReviewController;

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{news}', [NewsController::class, 'show']);
Route::get('/news/{news}/comments', [NewsCommentController::class, 'index']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/news/{news}/comments', [NewsCommentController::class, 'store']);
    Route::post('/campus_reviews/{campus}', [CampusReviewController::class, 'store']);
    Route::post('/campus_reviews/{id}/edit', [CampusReviewController::class, 'update']);
});

Route::get('/province', [ProvinceController::class, 'index']);
Route::get('/cities', [CityController::class, 'index']);
Route::get('/districts', [DistrictController::class, 'index']);
Route::get('/degree_levels', [DegreeLevelController::class, 'index']);
Route::get('/accreditations', [AccreditationController::class, 'index']);
Route::get('/campus_types', [CampusTypeController::class, 'index']);
Route::get('/campuses', [CampusController::class, 'index']);
Route::get('/campus/{campus:id}', [CampusController::class, 'show']);
Route::get('/campus/{campus:id}/reviews', [CampusController::class, 'reviews']);
Route::get('/campus/{campus:id}/faculties', [CampusController::class, 'faculties']);
Route::get('/faculties/{faculty:id}/study_programs', [FacultyController::class, 'study_programs']);
Route::get('/study_programs', [StudyProgramController::class, 'index']);
