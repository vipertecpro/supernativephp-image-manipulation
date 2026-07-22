<?php

use App\NativeComponents\AdjustPhoto;
use App\NativeComponents\CoverPhoto;
use App\NativeComponents\FilterPhoto;
use App\NativeComponents\Home;
use App\NativeComponents\ImageStudio;
use App\NativeComponents\ProfilePhoto;
use Illuminate\Support\Facades\Route;

Route::native('/', Home::class);
Route::native('/image-studio', ImageStudio::class);
Route::native('/profile-photo', ProfilePhoto::class);
Route::native('/cover-photo', CoverPhoto::class);
Route::native('/adjust-photo', AdjustPhoto::class);
Route::native('/filter-photo', FilterPhoto::class);
