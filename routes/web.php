<?php

use App\Livewire\Layout\MainLayout;
use App\Livewire\Pages\Attendance;
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\Report;
use App\Livewire\Pages\Worker;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/app', MainLayout::class);

Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/absensi', Attendance::class)->name('absensi');
Route::get('/worker', Worker::class)->name('worker');
Route::get('/report', Report::class)->name('report');
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

