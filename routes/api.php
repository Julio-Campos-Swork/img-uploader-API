<?php

use App\Http\Controllers\ImgUploadController;
use Illuminate\Support\Facades\Route;

Route::post('/uploadIMG',[ImgUploadController::class,'setImage']);
Route::get('/getIMG/{imageName',[ImgUploadController::class,'getImage']);
Route::post('/deleteIMG',[ImgUploadController::class,'deleteImage']);
Route::get('/getAllIMG',[ImgUploadController::class,'getAllImages']);

