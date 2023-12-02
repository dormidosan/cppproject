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
    //return view('welcome');
    //Prevent Laravel Homepage
    return \redirect('login');
});

Route::post('/testing', function () {
    return "FUNCIONANDO";
});

Route::post('/norole', function () {
    return "You have no ROLE here!.";
});

Route::get('serverenv', function() {
    $env = getenv(); // Retrieves all server environment variables
    echo "*". php_uname();
    // Display server environment variables
    foreach ($env as $key => $value) {
        echo "$key: $value <br>";
    }
});

Route::get('/testing/snsmail', [TestingController::class, 'snsmail'])->name('testing.snsmail');
Route::get('/testing/subscribesns', [TestingController::class, 'subscribesns'])->name('testing.subscribesns');
Route::get('/testing/traitstesting', [TestingController::class, 'traitstesting'])->name('testing.traitstesting');
Route::get('/testing/testredis', [TestingController::class, 'testredis'])->name('testing.testredis');



Route::get('/dashboard', function () {
    return view('dashboard');
    //Prevent Laravel Homepage
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
});

/*DASHBOARD FOR FLIGHT*/

Route::get('/token', function () {
    return csrf_token();
});



Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/flights/iata', [FlightController::class, 'iata'])->name('flights.iata');
    Route::get('/flights/search', [FlightController::class, 'search_timetable'])->name('flights.search');



    /*TESTING */
    Route::get('/flights/s3files', [FlightController::class, 's3files'])->name('flights.s3files');
    //Route::get('/flights/s3function', [FlightController::class, 's3function'])->name('flights.s3function');
    //Route::get('/flights/upload', [FlightController::class, 'upload'])->name('flights.upload');
    Route::get('/flights/lambda', [FlightController::class, 'lambda'])->name('flights.lambda');
    Route::get('/flights/mylibrary', [FlightController::class, 'mylibrary'])->name('flights.mylibrary');
    /*TESTING */



    Route::resource('flights', FlightController::class);

    Route::resource('travels', TravelController::class);


});

