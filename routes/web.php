<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\homepagecontroller;
use App\Http\Controllers\logincontroller;
use App\Http\Controllers\profilepage;
use App\Http\Controllers\logoutcontroller;
use App\Http\Controllers\allpetcontroller;
use App\Http\Controllers\petdetilscontroller;
use App\Http\Controllers\settingscontroller;
use App\Http\Controllers\messagescontroller;
use App\Http\Controllers\listapetcontroller;
use App\Http\Controllers\mylistingcontroller;
use App\Http\Controllers\myapplicationcontroller;


Route::get('/', [homepagecontroller::class, 'index']);
Route::get('/how-it-works', [homepagecontroller::class, 'howItWorks']);
Route::get('/login', [logincontroller::class, 'index']);
Route::post('/login', [logincontroller::class, 'loginuser']);
Route::post('/auth/google', [logincontroller::class, 'googleAuth'])->middleware('throttle:20,1');
Route::get('/browse', [allpetcontroller::class, 'index']);
Route::get('/Browse', [allpetcontroller::class, 'index']);
Route::get('/petdetails/{id}', [petdetilscontroller::class, 'index']);

Route::middleware('login')->group(function () {
    Route::get('/profile', [profilepage::class, 'index']);
    Route::get('/lost-pet-requests', [profilepage::class, 'lostPetRequests']);
    Route::post('/lost-pet-requests', [profilepage::class, 'storeLostPetRequest']);
    Route::post('/profile/lost-pet-request', [profilepage::class, 'storeLostPetRequest']);
    Route::get('/logout', [logoutcontroller::class, 'logoutuser']);
    Route::get('/settings', [settingscontroller::class, 'index']);
    Route::post('/settings/profile', [settingscontroller::class, 'updateProfile']);
    Route::post('/settings/password', [settingscontroller::class, 'updatePassword']);
    Route::get('/messages', [messagescontroller::class, 'index']);
    Route::post('/messages', [messagescontroller::class, 'store']);
    Route::get('/listapet', [listapetcontroller::class, 'index']);
    Route::post('/listapet', [listapetcontroller::class, 'storepet']);
    Route::get('/mylisting', [mylistingcontroller::class, 'index']);
    Route::get('/mylisting/{id}/edit', [mylistingcontroller::class, 'edit']);
    Route::post('/mylisting/{id}/edit', [mylistingcontroller::class, 'update']);
    Route::get('/mylisting/{id}/applications', [mylistingcontroller::class, 'applications']);
    Route::post('/mylisting/{petId}/applications/{applicationId}/status', [mylistingcontroller::class, 'updateApplicationStatus']);
    Route::get('/myapplication', [myapplicationcontroller::class, 'index']);
    Route::post('/petdetails/{id}/apply', [petdetilscontroller::class, 'apply']);
    Route::post('/mylisting/{id}/adopt', [mylistingcontroller::class, 'markAdopted']);
});
