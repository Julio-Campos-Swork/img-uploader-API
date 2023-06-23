<?php

use App\Http\Controllers\ImgUploadController;
use App\Http\Controllers\WikicatController;
use Illuminate\Support\Facades\Route;

Route::post('/uploadIMG',[ImgUploadController::class,'setImage']);
Route::post('/uploadIMGByUrl',[ImgUploadController::class,'uploadIMGByUrl']);
Route::get('/getIMG/{imageName',[ImgUploadController::class,'getImage']);
Route::post('/deleteImage',[ImgUploadController::class,'deleteImage']);
Route::get('/getAllImages',[ImgUploadController::class,'getAllImages']);

//WikiCat
Route::get('/getAllBreeds', [WikicatController::class,'getAllBreeds']);
