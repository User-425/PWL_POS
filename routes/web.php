<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;

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

Route::get('/', [WelcomeController::class, 'index']);

// Route::get('/about', [AboutController::class, 'index']);

// Route::get('/user/{name}', function ($name) {
//     return 'Nama saya ' . $name;
// });

// Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
//     return 'Pos ke-' . $postId . " Komentar ke-: " . $commentId;
// });

// Route::get('/articles/{id}', [ArticleController::class, 'index']);

// Route::get('/user/{name?}', function ($name = 'John') {
//     return 'Nama saya ' . $name;
// });

// Route::get('/hello', [WelcomeController::class, 'hello']);

// Route::get('/world', function () {
//     return 'World';
// });

// Route::resource('photos', PhotoController::class);

// Include only specific methods (Whitelist method)
// Route::resource('photos', PhotoController::class)->only([
//     'index',
//     'show'
// ]);
//
// Exclude specific methods (Blacklist method)
// Route::resource('photos', PhotoController::class)->except([
//     'create',
//     'store',
//     'update',
//     'destroy'
// ]);

Route::get('/greeting', [WelcomeController::class, 'greeting']);

Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);

Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);

