<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpensesController;

Route::get('/expenses', [ExpensesController::class,'index']);

Route::get('/expenses/{id}',[ExpensesController::class,'show']);

Route::post('/expenses',[ExpensesController::class,'store']);

Route::delete('/expenses/{id}',[ExpensesController::class,'destroy']);

Route::put('/expenses/{id}',[ExpensesController::class,'update']);

Route::patch('/expenses/{id}',[ExpensesController::class,'updatePartial']);