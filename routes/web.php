<?php

use App\Http\Controllers\EncodeRSAController;
use App\Http\Controllers\GenerateKeyController;
use App\Http\Controllers\ProfileController;
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




Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [GenerateKeyController::class, 'index'])->middleware('auth')->name('dashboard');

    Route::get('/generate-key', [GenerateKeyController::class, 'create'])->name('generate-key.create');
    Route::post('/generate-key', [GenerateKeyController::class, 'store'])->name('generate-key.store');

    Route::delete('/generate-key/{id}', [GenerateKeyController::class, 'destroy'])->name('generate-key.destroy');
    Route::get('/generate-key/{id}/download_public', [GenerateKeyController::class, 'downloadPublicKey'])->name('generate-key.down_public');
    Route::get('/generate-key/{id}/download_private', [GenerateKeyController::class, 'downloadPrivateKey'])->name('generate-key.down_private');

    Route::get('/encode-rsa', [EncodeRSAController::class, 'index'])->name('encode-rsa.index');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
