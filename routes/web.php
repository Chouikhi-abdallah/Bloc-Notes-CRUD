<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Models\Page;

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
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
Route::delete('/cards/{card}', function (Page $card) {
    $card->delete();

    return with('success', 'Card deleted successfully.');
})->name('cards.destroy');

Route::resource('pages', PageController::class)->middleware(['auth']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [PageController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::post('/pages/{page}/update', [PageController::class, 'updateContent'])->name('pages.updateContent');

require __DIR__.'/auth.php';
