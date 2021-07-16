<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AnswerVoteController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionVoteController;
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

Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login'])
    ->name('login');

Route::get('show-question', [QuestionController::class, 'display'])
    ->name('question.display');

Route::get('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])
    ->name('auth.logout');

Route::get('questionPost', [QuestionController::class, 'index'])
    ->name('question.index');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('question', [QuestionController::class, 'store'])
        ->name('question.store');
    Route::post('answer', [AnswerController::class, 'store'])
        ->name('answer.store');
    Route::post('question-vote', [QuestionVoteController::class, 'store'])
        ->name('question-answer.store');
    Route::post('answer-vote', [AnswerVoteController::class, 'store'])
        ->name('question-answer.store');
    Route::get('user', [\App\Http\Controllers\Api\AuthController::class, 'checkUser'])
        ->name('auth.user');
    Route::get('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])
        ->name('auth.logout');
    Route::put('question/edit',[QuestionController::class,'update'])
        ->name('question.update');
    Route::delete('question/delete',[QuestionController::class,'destroy'])
        ->name('question.delete');
    Route::put('answer/edit',[AnswerController::class,'update'])
        ->name('answer.update');
    Route::delete('answer/delete',[AnswerController::class,'destroy'])
        ->name('answer.destroy');
});
