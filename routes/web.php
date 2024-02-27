<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('user.home');
})->name('home');

// Volt::route('/', 'usercreate');
