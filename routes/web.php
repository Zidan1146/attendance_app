<?php

use App\Livewire\Init;
use App\Livewire\Pages\Attendance;
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Crud\CreateWorker;
use App\Livewire\Pages\Crud\EditWorker;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\Report;
use App\Livewire\Pages\Worker;
use Illuminate\Support\Facades\Route;

Route::get('/', Init::class);

Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/absensi', Attendance::class)->name('absensi');
Route::get('/worker', action: Worker::class)->name('worker');
Route::get('/worker/create', CreateWorker::class)->name('worker.create');
Route::get('/worker/edit/{id}', EditWorker::class)->name('worker.edit');
Route::get('/report', Report::class)->name('report');
Route::get('/login', Login::class)->name('login');
