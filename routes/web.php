<?php

use App\Livewire\Layout\MainLayout;
use App\Livewire\Pages\Attendance;
use App\Livewire\Pages\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/app', MainLayout::class);

Route::get('/dashboard', Dashboard::class)->name('dashboard');

Route::get('/absensi', Attendance::class)->name('absensi');


