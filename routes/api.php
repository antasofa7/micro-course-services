<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ImageCourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Mentor
Route::get('mentors',[MentorController::class, 'index']);
Route::get('mentors/{id}',[MentorController::class, 'show']);
Route::post('mentors', [MentorController::class, 'create']);
Route::patch('mentors/{id}', [MentorController::class, 'update']);
Route::delete('mentors/{id}', [MentorController::class, 'destroy']);

// API Course
Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/{id}', [CourseController::class, 'show']);
Route::post('courses', [CourseController::class, 'create']);
Route::patch('courses/{id}', [CourseController::class, 'update']);
Route::delete('courses/{id}', [CourseController::class, 'destroy']);

// API Chapter
Route::get('chapters', [ChapterController::class, 'index']);
Route::get('chapters/{id}', [ChapterController::class, 'show']);
Route::post('chapters', [ChapterController::class, 'create']);
Route::patch('chapters/{id}', [ChapterController::class, 'update']);
Route::delete('chapters/{id}', [ChapterController::class, 'destroy']);

// API Lessons
Route::get('lessons', [LessonController::class, 'index']);
Route::get('lessons/{id}', [LessonController::class, 'show']);
Route::post('lessons', [LessonController::class, 'create']);
Route::patch('lessons/{id}', [LessonController::class, 'update']);
Route::delete('lessons/{id}', [LessonController::class, 'destroy']);

// API Image Courses
Route::post('image-courses', [ImageCourseController::class, 'create']);
Route::delete('image-courses/{id}', [ImageCourseController::class, 'destroy']);

// API My Courses
Route::post('my-courses', [MyCourseController::class, 'create']);
Route::get('my-courses', [MyCourseController::class, 'index']);
Route::post('my-courses/premium', [MyCourseController::class, 'createPremiumAccess']);

// API Reviews
Route::post('reviews', [ReviewController::class, 'create']);
Route::patch('reviews/{id}', [ReviewController::class, 'update']);
Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);