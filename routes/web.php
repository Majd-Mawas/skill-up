<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\Training\HallController;
use App\Http\Controllers\Training\CourseLevelController;

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

require __DIR__ . '/auth.php';
require __DIR__ . '/phone-verification.php';
require __DIR__ . '/training.php';

Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::name('web.')->group(function () {
        Route::resource('halls', HallController::class);
        Route::prefix('courses')->group(function () {
            Route::get('courses/{course}/levels', [CourseLevelController::class, 'index'])->name('courses.levels.index');
            Route::get('courses/{course}/levels/create', [CourseLevelController::class, 'create'])->name('courses.levels.create');
            Route::post('courses/{course}/levels', [CourseLevelController::class, 'store'])->name('courses.levels.store');
            Route::get('courses/{course}/levels/{level}/edit', [CourseLevelController::class, 'edit'])->name('courses.levels.edit');
            Route::put('courses/{course}/levels/{level}', [CourseLevelController::class, 'update'])->name('courses.levels.update');
            Route::delete('courses/{course}/levels/{level}', [CourseLevelController::class, 'destroy'])->name('courses.levels.destroy');
            Route::post('courses/{course}/levels/reorder', [CourseLevelController::class, 'reorder'])->name('courses.levels.reorder');
        });
    });
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('/home', fn() => view('index'))->name('home');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});

Route::get('lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ar'])) {
        abort(400); // Invalid language
    }

    session(['locale' => $locale]);
    app()->setLocale($locale);

    return redirect()->back();
})->name('lang.switch');

// Course Levels Routes
Route::middleware(['auth'])->group(function () {});
