<?php

use App\Http\Controllers\Pdf\DownloadDailyReports;
use App\Http\Controllers\Pdf\DownloadMonthlyReports;
use App\Livewire\Init;
use App\Livewire\Pages\Attendance;
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Crud\CreateWorker;
use App\Livewire\Pages\Crud\EditWorker;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\Report;
use App\Livewire\Pages\Role;
use App\Livewire\Pages\Worker;
use Illuminate\Support\Facades\Route;

Route::get('/', Init::class);

Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/absensi', Attendance::class)->name('absensi');
Route::get('/worker', Worker::class)->name('worker');
Route::get('/worker/create', CreateWorker::class)->name('worker.create');
Route::get('/worker/edit/{id}', EditWorker::class)->name('worker.edit');
Route::get('/report', Report::class)->name('report');
Route::get('/role', Role::class)->name('role');
Route::get('/login', Login::class)->name('login');

Route::get('/pdf/download/monthly', DownloadMonthlyReports::class)->name('pdf.download.monthly');
Route::get('/pdf/download/daily', DownloadDailyReports::class)->name('pdf.download.daily');
