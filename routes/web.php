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


//  * Ruta añadida para redireccionar a la misma pagina.    
Route::get('personita/{id}','GeneralController@getPersonById');  



Route::get('/{any}', 'HomeController@index')->where('any', '.*');

Route::get('files', 'FileController@index');
Route::post('upload', 'FileController@uploadFile');
Route::get('delete/upload-folder/{file}', 'FileController@deleteFile');
Route::get('download/upload-folder/{file}', 'FileController@downloadFile');

/*
// *** - Comentado por Lionel - ***
Route::get('/', function () {
    return view('welcome');
});
*/