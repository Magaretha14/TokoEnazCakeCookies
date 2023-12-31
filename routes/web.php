<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Models\SubKategori;

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

Route::get('/', [Controllers\HomeController::class, 'index'])->name('home.index');
Route::get('produk/{kategori}', [Controllers\HomeController::class, 'produk'])->name('home.produk');
Route::get('kategori/{kategori}', [Controllers\HomeController::class, 'kategori'])->name('home.kategori');
Route::get('subkategori/{subkategori}', [Controllers\HomeController::class, 'subkategori'])->name('home.subkategori');
Route::get('search', [Controllers\HomeController::class, 'search'])->name('home.search');
Route::get('home', [Controllers\HomeController::class, 'redir_admin'])->name('home.redir_admin');

Route::group(['middleware' => 'revalidate'], function() {
    Auth::routes(['register' => false,'reset' => false]);
    Route::get('admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    // route produk
    Route::prefix('admin/produk')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'produk'])->name('admin.produk');
        Route::get('delete/{kategori}', [App\Http\Controllers\AdminController::class, 'delete_produk'])->name('admin.delete_produk');
        Route::get('edit/', [App\Http\Controllers\AdminController::class, 'edit_produk'])->name('admin.edit_produk');
        Route::post('create', [App\Http\Controllers\AdminController::class, 'create_produk'])->name('admin.create_produk');
        Route::post('update', [App\Http\Controllers\AdminController::class, 'update_produk'])->name('admin.update_produk');
        Route::patch('makebs/{kategori}', [App\Http\Controllers\AdminController::class, 'make_bestseller'])->name('admin.make_bestseller');
        Route::patch('removebs/{kategori}', [App\Http\Controllers\AdminController::class, 'remove_bestseller'])->name('admin.remove_bestseller');
    });
    // route kategori
    Route::prefix('admin/kategori')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'kategori'])->name('admin.kategori');
        Route::get('delete/{kategori}', [App\Http\Controllers\AdminController::class, 'delete_kategori'])->name('admin.delete_kategori');
        Route::post('create', [App\Http\Controllers\AdminController::class, 'create_kategori'])->name('admin.create_kategori');
        Route::post('update', [App\Http\Controllers\AdminController::class, 'update_kategori'])->name('admin.update_kategori');
    });
    // route profil
    Route::prefix('admin/profil')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'profil'])->name('admin.profil');
        Route::post('update', [App\Http\Controllers\AdminController::class, 'update_profil'])->name('admin.update_profil');
    });
    //route subkategori
    Route::prefix('admin/subkategori')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'subkategori'])->name('admin.subkategori');
        Route::post('update', [App\Http\Controllers\AdminController::class,'update_subkategori'])->name('admin.update_subkategori');
        Route::post('create', [App\Http\Controllers\AdminController::class, 'create_subkategori'])->name('admin.create_subkategori');
        Route::get('delete/{subkategori}', [App\Http\Controllers\AdminController::class, 'delete_subkategori'])->name('admin.delete_subkategori');
    });
});
