<?php

use App\Http\Controllers\BackUpFileController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
    return view('welcome');
})->name('welcome');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['auth', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
        'as' => 'admin.'
    ],
    function () {
       /*  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
       */

        // users
        Route::get('/users/{id}', [UserController::class, 'userActions'])->name('dashboard.user.showAction');
        Route::get('/users', [UserController::class, 'index'])->name('dashboard.users');
        //groups
        Route::get('/groups', [GroupController::class, 'index'])->name('dashboard.groups.index');
        Route::delete('/groups/{id}/destroy', [GroupController::class, 'destroy'])->name('dashboard.groups.destroy');
        Route::post('/groups/store', [GroupController::class, 'store'])->name('dashboard.groups.store');
        Route::post('/groups/{id}/users/addUser', [GroupController::class, 'addUserTogroup'])->name('dashboard.group.addUser');
        Route::delete('/groups/{id}/users/removeUser', [GroupController::class, 'removeUserFromGroup'])->name('dashboard.group.removeUser');
        Route::get('/groups/{id}/users', [GroupController::class, 'groupUsers'])->name('dashboard.group.users');
        Route::get('/groups/{id}/files', [GroupController::class, 'groupFiles'])->name('dashboard.group.files');
        /// files ///
        Route::get('/files/{id}/destroy', [FileController::class, 'destroy'])->name('dashboard.files.destroy');
        Route::get('/files/{id}/download', [FileController::class, 'downloadFile'])->name('dashboard.files.download');
        Route::get('/files/{id}/actions', [FileController::class, 'fileActions'])->name('dashboard.files.actions');
        Route::post('/files/{id}/checkOut', [FileController::class, 'checkOut'])->name('dashboard.files.checkOut');
        Route::get('/files/{id}/previousVersions', [BackUpFileController::class, 'previousVersions'])->name('dashboard.files.previousVersions');
        Route::get('/files/{id}/downloadOldVersion', [BackUpFileController::class, 'downloadOldVersion'])->name('dashboard.files.downloadOldVersion');

        Route::post('/files/{id}/recoverFile ', [BackUpFileController::class, 'recoverFile'])->name('dashboard.files.recoverFile');

        Route::get('/files/pending', [FileController::class, 'pendingFiles'])->name('dashboard.files.pending');
        Route::get('/files/active', [FileController::class, 'activeFiles'])->name('dashboard.files.active');
        Route::post('/files/store', [FileController::class, 'store'])->name('dashboard.files.store');
        Route::post('/files/multiCheckIn', [FileController::class, 'multiCheckIn'])->name('dashboard.files.multiCheckIn');
        Route::post('/files/multiApprove', [FileController::class, 'multiApprove'])->name('dashboard.files.multiApprove');
        Route::get('/files/checkedIn', [FileController::class, 'checkedInFiles'])->name('dashboard.files.checkedIn');


    }
);

require __DIR__ . '/auth.php';
