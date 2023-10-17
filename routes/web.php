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
//Route::get('persona', 'GeneralController@getPersonByCI');
//Route::post('newPerson', 'GeneralController@saveNewPerson');
Route::get('person/{id}', 'GeneralController@getPersonById');
//Route::get('description/{abr}', 'DocumentController@getDescriptionByAbr');
Route::post('notification', 'GeneralController@PPENotification');
Route::get('notification2/{id}', 'GeneralController@PPENotification2');

Route::get('/{any}', 'HomeController@index')->where('any', '.*');

//  * Ruta añadida para redireccionar a la misma pagina.

Route::get('files', 'FileController@index');
Route::post('upload', 'FileController@uploadFile');
Route::get('delete/upload-folder/{file}', 'FileController@deleteFile');
Route::get('download/upload-folder/{file}', 'FileController@downloadFile');
Route::get('reportSelectedFixedAssets2/', 'FixedAssetController@getReportSelectedFixedAssets2');

//  |--------------------------------------------------------------------------
//  | Rutas API para el Sistema de Elecciones
//  |--------------------------------------------------------------------------
//  * E1 . Obtener la persona por su carnet de identidad y la mesa donde deberia realizar su boto
Route::post('getAuthorizedPerson', 'ElectionController@getAuthorizedPerson');
//  * E2 . Obtener la persona por su carnet de identidad y si se encuentra habilitado
Route::post('getTableElection', 'ElectionController@getTableElection');
//  * E3 . Obtener la lista de mesas habilitadas para la eleccion
Route::post('getInformationTablets', 'ElectionController@getInformationTablets');
//  * E4 . Obtener la lista de mesas habilitadas para la eleccion
Route::get('reportInformationPerson', 'ElectionController@reportInformationPerson');
//Route::get('report', 'ElectionController@report');

/*
Route::get('/', function () {
return view('welcome');
});*/
