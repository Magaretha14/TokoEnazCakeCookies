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
Route::get('produk/{category}', [Controllers\HomeController::class, 'produk'])->name('home.produk');
Route::get('kategori/{category}', [Controllers\HomeController::class, 'kategori'])->name('home.kategori');
Route::get('search', [Controllers\HomeController::class, 'search'])->name('home.search');
Route::get('home', [Controllers\HomeController::class, 'redir_admin'])->name('home.redir_admin');

Route::group(['middleware' => 'revalidate'], function() {
    Auth::routes(['register' => false,'reset' => false]);
    Route::get('admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    // route produk
    Route::prefix('admin/produk')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'produk'])->name('admin.produk');
        Route::get('delete/{category}', [App\Http\Controllers\AdminController::class, 'delete_produk'])->name('admin.delete_produk');
        Route::post('edit', [App\Http\Controllers\AdminController::class, 'edit_produk'])->name('admin.edit_produk');
        Route::post('create', [App\Http\Controllers\AdminController::class, 'create_produk'])->name('admin.create_produk');
        Route::post('update', [App\Http\Controllers\AdminController::class, 'update_produk'])->name('admin.update_produk');
        Route::patch('makebs/{category}', [App\Http\Controllers\AdminController::class, 'make_bestseller'])->name('admin.make_bestseller');
        Route::patch('removebs/{category}', [App\Http\Controllers\AdminController::class, 'remove_bestseller'])->name('admin.remove_bestseller');
    });
    // route kategori
    Route::prefix('admin/kategori')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'kategori'])->name('admin.kategori');
        Route::get('delete/{category}', [App\Http\Controllers\AdminController::class, 'delete_kategori'])->name('admin.delete_kategori');
        Route::post('create', [App\Http\Controllers\AdminController::class, 'create_kategori'])->name('admin.create_kategori');
        Route::post('update', [App\Http\Controllers\AdminController::class, 'update_kategori'])->name('admin.update_kategori');
    });
    // route profil
    Route::prefix('admin/profil')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'profil'])->name('admin.profil');
        Route::post('update', [App\Http\Controllers\AdminController::class, 'update_profil'])->name('admin.update_profil');
    });
    //route subkategori
    Route::prefix('subkategori')->group(function () {
        Route::get('/', [App\Http\Controllers\SubKategoriController::class, 'subkategori'])->name('subkategori');
        Route::post('update', [App\Http\Controllers\SubKategoriController::class,'update_subkategori'])->name('update_subkategori');
        Route::post('create', [App\Http\Controllers\SubKategoriController::class, 'create_subkategori'])->name('create_subkategori');
        Route::get('delete/{subkategori}', [App\Http\Controllers\SubKategoriController::class, 'delete_subkategori'])->name('delete_subkategori');
    });
});
