<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::post('login', 'GeneralController@searchUser');
//Route::get('DeliveryDocuments','DeliveryDocumentsController@getListDeliveryDocument');
Route::group([
    'middleware' => 'jwt.auth',
    //'namespace' => 'App\Http\Controllers',
    //'prefix' => 'auth'
//], function ($router) {
], function () {
    //Route::post('login', 'Controller@login');
    Route::post('logout', 'GeneralController@logoutUser');
    Route::post('profiles', 'GeneralController@registerUserProfiles');
    Route::post('years', 'GeneralController@registerUserYears');
    Route::get('DeliveryDocuments','DeliveryDocumentsController@getListDeliveryDocument');
    Route::post('editDocument','DeliveryDocumentsController@getDocumentsByInCharge');
    //Route::post('token', 'GeneralController@checkTokenUser');
    
// *** - rutas para crear, editar, mostrar, buscar e imprimir a una persona - ***
    Route::post('persons', 'GeneralController@getPersonsByDescription');
    Route::get('person/add','GeneralController@addPerson');
    Route::post('person','GeneralController@storePerson');    
    Route::get('person/edit/{id}','GeneralController@editPerson');    
    Route::get('person/show/{id}','GeneralController@showPerson');    

    /*
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    */
});