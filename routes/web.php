<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $records = \App\Models\Attendance::whereMonth('date', Carbon::now()->month)->orderBy('date', 'asc')->get();
    return view('dashboard', compact('records'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('attendance', AttendanceController::class);

require __DIR__.'/auth.php';
