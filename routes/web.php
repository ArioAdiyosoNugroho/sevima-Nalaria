<?php

use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', [DiagnosticController::class, 'index'])->name('diagnostic.landing');

// Protected Routes (Siswa must be logged in to take assessment and view results)
Route::middleware('auth')->group(function () {
    Route::get('/quiz', [DiagnosticController::class, 'quiz'])->name('diagnostic.quiz');
    Route::post('/quiz/submit', [DiagnosticController::class, 'submit'])->name('diagnostic.submit');
    Route::get('/result/{code}', [DiagnosticController::class, 'result'])->name('diagnostic.result');
    Route::post('/result/{code}/practice/{questionId}', [DiagnosticController::class, 'submitPractice'])->name('diagnostic.practice.submit');
    Route::get('/history', [DiagnosticController::class, 'history'])->name('diagnostic.history');

    // Dashboard redirects directly to history
    Route::get('/dashboard', function () {
        return redirect()->route('diagnostic.history');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
