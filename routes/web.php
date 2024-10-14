<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Controller; // Controllerをインポート
use Illuminate\Support\Facades\Route;

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



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// この部分を修正
Route::get('/api-call/{prompt}', [Controller::class, 'callGeminiApi']); // 直接呼び出す形式
Route::get('/', [Controller::class, 'home']);
Route::post('/', [Controller::class, 'home']);



require __DIR__.'/auth.php';