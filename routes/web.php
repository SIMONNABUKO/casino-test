<?php

use App\Http\Controllers\CasinoGamesController;
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

Route::get('/', [CasinoGamesController::class,'home'])->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/update-games', [CasinoGamesController::class,'updateGames'])->middleware('auth');
Route::get('/games/{game}',[CasinoGamesController::class,'openGame'])->middleware('auth');
