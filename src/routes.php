<?php

use Illuminate\Support\Facades\Route;
use Omnia\Oalivechat\Controllers\AdminLiveChatController;
use Omnia\Oalivechat\Controllers\LiveChatController;

Route::group(['middleware' => 'web'], function () {

    Route::post('/save-data', [LiveChatController::class, 'saveData'])->name('saveData');

    Route::get('/getChat', [LiveChatController::class, 'getChat'])->name('getChat');

    Route::get('/image/{id}', [LiveChatController::class, 'getImage'])->name('image');
});


Route::group(['middleware' => 'web'], function () 
{
    Route::get('chat', [AdminLiveChatController::class, 'chat'])->name('admin.chat')->middleware('adminMessages');
    
    Route::post('storeChat/{userId}', [AdminLiveChatController::class, 'storeChat'])->name('storeChat');

    Route::get('/viewChat/{id}', [AdminLiveChatController::class, 'viewChat'])->name('viewChat');

    Route::get('/getChat/{id}', [AdminLiveChatController::class, 'getChat'])->name('getChatAdmin');

    Route::get('/fetchNewMessages', [AdminLiveChatController::class, 'fetchNewMessages'])->name('fetchNewMessages');

    Route::put('/updateStatus/{id}', [AdminLiveChatController::class, 'updateStatus'])->name('updateStatus');
});




