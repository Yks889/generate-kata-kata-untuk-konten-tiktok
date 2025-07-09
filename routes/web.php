<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentController;

Route::get('/', fn() => redirect('/generate-form'));
Route::get('/generate-form', [ContentController::class, 'form']);
Route::post('/generate', [ContentController::class, 'handleGenerate']);
Route::post('/generate-gemini', [ContentController::class, 'generateFromGemini']);


