<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [QuizController::class, 'index'])->name('dashboard');
Route::post('/quiz/generate', [QuizController::class, 'generate'])->middleware('throttle:generate-quiz')->name('quiz.generate');
Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
Route::delete('/quiz/{quiz}', [QuizController::class, 'destroy'])->name('quiz.destroy');
