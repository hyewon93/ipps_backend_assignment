<?php

use App\Models\Lesson;
use App\Events\LessonWatched;
use App\Http\Controllers\AchievementsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);

// Dispatch 'LessonWatched' Event for test
Route::get('/watchLesson', function (Request $request) {

    LessonWatched::dispatch(Lesson::where('id', 2)->first(), Auth::user());
});

/* 
*   User Authentication
*/
Route::get('auth', function () {
    $credentials = [
        'email'    => 'johnD@example.com',
        'password' => 'password'
    ];

    if (! Auth::attempt($credentials)) {
        return 'Incorrect username and password combination';
    }

    return redirect('protected');
});

Route::get('auth/logout', function () {
    Auth::logout();

    return 'See you again.';
});

Route::get('protected', function () {
    if (! Auth::check()) {
        return 'Illegal Access!';
    }

    return 'Welcome back, ' . Auth::user()->name;
});
