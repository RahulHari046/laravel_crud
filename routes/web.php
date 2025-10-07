<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome1');
});

Route::post('/store', [UserController::class, 'store']);
Route::get('/getdetails', [UserController::class, 'getdetails']);
Route::post('/deletedetails', [UserController::class, 'deletedetails']);
Route::post('/editdetails', [UserController::class, 'editdetails']);
Route::post('/updatedetails', [UserController::class, 'updatedetails']);
