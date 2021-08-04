<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AnswerVoteController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionVoteController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
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

Route::get('/guest/questionPost', [QuestionController::class, 'index'])
    ->name('question.index');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('question', [QuestionController::class, 'store'])
        ->name('question.store');
    Route::get('questionPost', [QuestionController::class, 'index'])
        ->name('question.index');
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
    Route::get('chat',[ThreadController::class,'index'])
        ->name('thread.index');
    Route::get('chat/{user}',[ThreadController::class,'create'])
        ->name('thread.create');
    Route::get('thread-message/{id}',[MessageController::class,'index'])
        ->name('message.index');
    Route::post('message',[MessageController::class,'store'])
        ->name('message.store');
    Route::get('users/all',[UserController::class,'index'])
        ->name('user.index');
});
