<?php


use App\Http\Controllers\FlightController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\TravelController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\ProfileController;

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

Route::get('/', function () {
    return \redirect('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
    //Prevent Laravel Homepage
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__ . '/auth.php';
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
});



//Routes for CPP Project. Only when logged as User
Route::middleware(['auth', 'role:user'])->group(function () {

    //Search the airport
    Route::get('/flights/iata', [FlightController::class, 'iata'])->name('flights.iata');

    //Search the flight
    Route::get('/flights/search', [FlightController::class, 'search_timetable'])->name('flights.search');

    //Resource for flights
    Route::resource('flights', FlightController::class);

    //Resource for travels
    Route::resource('travels', TravelController::class);
});

