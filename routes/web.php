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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//category route starts here

Route::resource('categories','CategoryController');

//category route ends here

//subcategory route starts here

Route::resource('subcategories','SubcategoryController');

//subcategory route ends here

//Brand Route Starts here

Route::resource('brands','BrandController');

//Brand Route Ends Here
