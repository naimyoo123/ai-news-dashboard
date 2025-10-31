<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

Route::post('/news', [NewsController::class, 'getNews']);
