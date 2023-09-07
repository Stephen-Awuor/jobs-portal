<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;
Use App\Models\Listing;

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

//All listings
Route::get('/', [ListingController::class, 'index']);

//Show create listing form
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');
 
//store form data
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

//show edit form
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

//update job 
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

//Delete job 
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

//Single listing
Route::get('/listings.show/{listing}', [ListingController::class, 'show']);

//show register/create form
Route::get('/register', [UserController::class, 'register_form'])->middleware('guest');

//Register user
Route::post('/users', [UserController::class, 'register_user']);

//Logout user
Route::get('/logout', [UserController::class, 'logout'])->middleware('auth');

//Login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

//login user 
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

//Manage listings when logged in 
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');