<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\API\AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function(Request $request){
        return ["success" => true, "message" => "Authenthicated", "data" => $request->user()];
    });
    Route::get('/logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);

    Route::get('/index-note-list', [\App\Http\Controllers\API\NoteListController::class, 'indexNoteList']);
    Route::post('/create-note-list', [\App\Http\Controllers\API\NoteListController::class, 'createNoteList']);
    Route::delete('/delete-note-list/{id}', [\App\Http\Controllers\API\NoteListController::class, 'deleteNoteList']);
    Route::get('/detail-note-list/{id}', [\App\Http\Controllers\API\NoteListController::class, 'detailNoteList']);
    Route::put('/update-note-list/{id}', [\App\Http\Controllers\API\NoteListController::class, 'updateNoteList']);
    Route::put('/update-status-note-list/{id}', [\App\Http\Controllers\API\NoteListController::class, 'updateStatusNoteList']);

    Route::post('/create-note-list-detail-item/{id}', [\App\Http\Controllers\API\NoteListController::class, 'createNoteListDetailItem']);
    Route::get('/detail-note-list-detail-item/{id}', [\App\Http\Controllers\API\NoteListController::class, 'detailNoteListDetailItem']);
    Route::delete('/delete-note-list-detail-item/{id}', [\App\Http\Controllers\API\NoteListController::class, 'deleteNoteListDetailItem']);

});

