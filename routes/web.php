<?php

use App\NativeComponents\Home;
use App\NativeComponents\ImageStudio;
use Illuminate\Support\Facades\Route;

Route::native('/', Home::class);
Route::native('/image-studio', ImageStudio::class);
