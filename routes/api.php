<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\QuestionController;
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

Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login'])
    ->name('login');

Route::get('logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])
    ->name('auth.logout');

Route::get('show-question', [App\Http\Controllers\QuestionController::class, 'display'])
    ->name('question.display');

Route::get('questionPost', [App\Http\Controllers\QuestionController::class, 'index'])
    ->name('question.index');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('test', function () {
        return response(['message' => 'success'], 200);
    });
    Route::post('/question', [QuestionController::class, 'store'])
        ->name('question.store');
    Route::post('/answer', [AnswerController::class, 'store'])
        ->name('answer.store');
});
