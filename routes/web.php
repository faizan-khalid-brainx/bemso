<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route for testing controller functions
Route::get('test', function () {
    $queryResult = \App\Models\Question::where('id', 1)->with('user', 'answers', 'user_votes')->get()->first();
//    $question = $queryResult->getRelations()['user_votes']->map(function ($user) {
//        return $user->getOriginal()['pivot_vote'];
//    });
//    $vote = array_count_values($question->toArray());
//    if (array_key_exists(0,$vote)){
//        $vote['0'] = 0;
//    }
//    $answers = $queryResult->answers()->with('user_votes')->get()->map(function ($answer) {
//        $returnable = Arr::only($answer->getAttributes(), ['id', 'content', 'created_at']);
//        $returnable['user'] = (object)Arr::only($answer->user()->get()->first()->getAttributes(), ['id', 'name', 'email']);
//        $vote = $answer->getRelations()['user_votes']->map(function ($user) {
//            return $user->getOriginal()['pivot_vote'];
//        });
//        $vote = array_count_values($vote->toArray());
//        if (!array_key_exists(0, $vote)) {
//            $vote['0'] = 0;
//        }
//        if (!array_key_exists(1, $vote)) {
//            $vote['1'] = 0;
//        }
//        $returnable['vote'] = $vote;
//        return (object)$returnable;
//    });
//    $answers = $queryResult->answers()->get()->map(function ($answer){
//       $returnable = Arr::only($answer->getAttributes(),['id','content','created_at']);
//       $returnable['user'] = (object)Arr::only($answer->user()->get()->first()->getAttributes(),['id','name','email']);
//       $returnable['vote'] = 0;
//       return (object)$returnable;
//    });
    ddd($answers);
//    ddd();
});
