<?php

use App\Http\Controllers\DiagnosticController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DiagnosticController::class, 'index'])->name('diagnostic.landing');
Route::get('/quiz', [DiagnosticController::class, 'quiz'])->name('diagnostic.quiz');
Route::post('/quiz/submit', [DiagnosticController::class, 'submit'])->name('diagnostic.submit');
Route::get('/result/{code}', [DiagnosticController::class, 'result'])->name('diagnostic.result');
Route::post('/result/{code}/practice/{questionId}', [DiagnosticController::class, 'submitPractice'])->name('diagnostic.practice.submit');
