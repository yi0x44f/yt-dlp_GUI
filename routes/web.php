<?php
use App\Http\Controllers\YoutubeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\FileListController;


Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/filelist', [FileListController::class, 'listFiles']);
Route::delete('/filelist', [FileListController::class, 'deleteFiles']);


// Handling YoutubeDownload
Route::get('/{filename}', function ($filename){
    $path = storage_path('app/public/video/' . $filename);
    return response()->download($path);
})->where('filename', '.*');
Route::post('/',[YoutubeController::class, 'download']);



