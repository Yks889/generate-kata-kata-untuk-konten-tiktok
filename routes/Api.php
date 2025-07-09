<?php

use App\Http\Controllers\ContentController;

Route::get('/generate', [ContentController::class, 'generate']);
