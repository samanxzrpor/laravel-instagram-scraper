<?php

use App\Http\Controllers\Admin\Home;
use App\Http\Controllers\Admin\Proxies;
use App\Http\Controllers\Admin\Robots;
use App\Http\Controllers\Admin\Users;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Redis;
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

Route::get('' , function (){

    return redirect()->route('login.showPage');
});

Route::prefix('auth')->group(function (){

    Route::get('register' , [Register::class , 'showPage'])->name('register.showPage');

    Route::post('register' , [Register::class , 'store'])->name('register.store');

    Route::get('login' , [Login::class , 'showPage'])->name('login.showPage');

    Route::post('login' , [Login::class , 'loginUser'])->name('login.loginUser');

});


Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('dashboard' , [Home::class , 'index'])->name('admin.home');

    Route::prefix('robots')->group(function () {

        Route::get('list' , [Robots::class , 'showAll'])->name('robots.list');

        Route::get('add' , [Robots::class , 'add'])->name('robots.add');

        Route::put('{robot_id}/change-proxy' , [Robots::class , 'changeProxy'])->name('robots.changeProxy');

        Route::post('store' , [Robots::class , 'store'])->name('robots.storeNew');

        Route::get('{robot_id}/edit' , [Robots::class , 'edit'])->name('robots.edit');

        Route::put('{robot_id}/update' , [Robots::class , 'update'])->name('robots.update');

        Route::delete('{robot_id}/delete' , [Robots::class , 'delete'])->name('robots.delete');
    });

    Route::prefix('proxies')->group(function () {

        Route::get('' , [Proxies::class , 'showAll'])->name('proxies.showAll');

        Route::get('add' , [Proxies::class , 'add'])->name('proxies.add');

        Route::get('check' , [Proxies::class , 'checkProxies'])->name('proxies.checkStatus');

        Route::post('store' , [Proxies::class , 'store'])->name('proxies.store');

        Route::delete('{proxy_id}/delete' , [Proxies::class , 'delete'])->name('proxies.delete');

        Route::delete('deleteAll' , [Proxies::class , 'deleteAll'])->name('proxies.deleteAll');
    });

    Route::prefix('users')->group(function () {

        Route::get('' , [Users::class , 'showAll'])->name('users.showAll');

        Route::get('add' , [Users::class , 'add'])->name('users.add');

        Route::post('store' , [Users::class , 'store'])->name('users.store');

        Route::get('{user_id}/edit' , [Users::class , 'edit'])->name('users.edit');

        Route::put('{user_id}/update' , [Users::class , 'update'])->name('users.update');

        Route::delete('{user_id}/delete' , [Users::class , 'delete'])->name('users.delete');

    });

});

Route::prefix('posts')->middleware('auth')->group(function () {

    Route::get('' , [PostsController::class , 'showAll'])->name('posts.showAll');

    Route::get('{post_id}/show' , [PostsController::class , 'single'])->where('post_id' , '[0-9]+')->name('posts.single');

    Route::get('scrap' , [PostsController::class , 'add'])->name('posts.scrap.showPage');

    Route::post('start' , [PostsController::class , 'startScrap'])->name('start.scrap');

    Route::delete('deleteAll' , [PostsController::class , 'deleteAll'])->name('posts.daleteAll');

    Route::delete('{post_id}/delete' , [PostsController::class , 'deleteOne'])->name('posts.deleteOne');
});

