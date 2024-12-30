<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Events\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('chats', [ChatController::class,'index'])->name('chats');
Route::get('/fetch-messages/{id}', [ChatController::class, 'fetchMessages']);
Route::post('/sendmessage', [ChatController::class, 'sendMessage']);
Route::get('/mark-as-read', [ChatController::class,'markAsRead'])->name('mark-as-read');
Route::get('/export-messages/{contactId}', [ChatController::class, 'exportMessages'])->name('export.messages');
require __DIR__.'/auth.php';
  