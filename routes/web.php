<?php

use App\Http\Controllers\HelpChatController;
use App\Http\Controllers\HelpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HelpController::class, 'show'])->name('help.show');
Route::get('/help', [HelpController::class, 'show']);
Route::post('/help/chat', [HelpChatController::class, 'stream'])->name('help.chat');
