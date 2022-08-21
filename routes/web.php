<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return to_route('login');
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])->group(function () {

    Route::get('dashboard',[\App\Http\Controllers\PrisonerController::class,'dashboard'])->name('dashboard');
    Route::get('prisoner/export', [\App\Http\Controllers\PrisonerController::class, 'export'])->name('prisoner.export');
    Route::resource('prisoner',\App\Http\Controllers\PrisonerController::class);

    Route::resource('prison',\App\Http\Controllers\JailOfficialController::class);
    Route::get('prisoner/{prisoner}/prisionerShifted/create',[\App\Http\Controllers\PrisionerShiftedController::class,'create'])->name('prisionerShifted.create');

    Route::get('prisoner/{prisoner}/assistance/create',[\App\Http\Controllers\AssistanceController::class,'create'])->name('prisoner.assistance');
    Route::post('assistance',[\App\Http\Controllers\AssistanceController::class,'store'])->name('assistance.store');
    Route::get('assistance',[\App\Http\Controllers\AssistanceController::class,'index'])->name('assistance.index');
    Route::delete('assistance/{assistance}',[\App\Http\Controllers\AssistanceController::class,'destroy'])->name('assistance.destroy');

    Route::post('prisionerShifted',[\App\Http\Controllers\PrisionerShiftedController::class,'store'])->name('prisionerShifted.store');
    Route::resource('prisonerCharges',\App\Http\Controllers\PrisonerChargesController::class);
    Route::get('report',[\App\Http\Controllers\ReportController::class,'reportMain'])->name('report.reportMain');
    Route::get('report/statistics',[\App\Http\Controllers\ReportController::class,'index'])->name('report.statistics');
    Route::get('report/crime-wise',[\App\Http\Controllers\ReportController::class,'crimeWise'])->name('report.crime-wise');
    Route::get('report/prison-wise',[\App\Http\Controllers\ReportController::class,'prisonWise'])->name('report.prison-wise');
    Route::get('report/region-wise',[\App\Http\Controllers\ReportController::class,'regionWise'])->name('report.region-wise');

});
