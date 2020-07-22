<?php

use App\DeliveryDocuments;
use App\Http\Controllers\DeliveryDocumentsController;
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
    //Route::post('token', 'GeneralController@checkTokenUser');

    // *** - rutas para crear, editar, mostrar, buscar e imprimir a DeliveryDocuments - ***
    Route::get('deliveryDocuments/responsable/{responsable}','DeliveryDocumentsController@searchResponsable' );
    Route::get('deliveryDocuments/cargos/{responsable}','DeliveryDocumentsController@loadCargos' );
    Route::get('deliveryDocuments/{gestion}','DeliveryDocumentsController@getListDeliveryDocument');
    Route::post('deliveryDocuments/edit','DeliveryDocumentsController@getDocumentsByInCharge');
    Route::get('deliveryDocuments/getRecursos/{gestion}','DeliveryDocumentsController@getListRecursos'); 
    Route::post('deliveryDocuments/store','DeliveryDocumentsController@updateStore');
    Route::post('deliveryDocuments/encargado/add','DeliveryDocumentsController@storeEncargado');
    Route::post('deliveryDocuments/asset/add','DeliveryDocumentsController@storeAsset');
    
    
    
    // *** - rutas para crear, editar, mostrar, buscar e imprimir a DonationDocuments - ***
    Route::get('donationDocuments/{gestion}','DonationDocumentsController@getListDonationDocument');
    Route::post('donationDocuments/edit','DonationDocumentsController@getDonationDocumentsByInCharge');
    Route::post('donationDocuments/store','DonationDocumentsController@updateStore');
    Route::get('donationDocuments/getRecursos/{gestion}','DonationDocumentsController@getListRecursos');
    Route::post('donationDocuments/encargado/add','DonationDocumentsController@storeEncargado');
    Route::post('donationDocuments/asset/add','DonationDocumentsController@storeAsset');

    // *** - rutas para crear, editar, mostrar, buscar e imprimir a CarpentryDocuments - ***
    Route::get('carpentryDocuments/{gestion}','CarpentryDocumentsController@getListCarpentryDocument');
    Route::post('carpentryDocuments/edit','CarpentryDocumentsController@getCarpentryDocumentsByInCharge');
    Route::post('carpentryDocuments/store','CarpentryDocumentsController@updateStore');
    Route::get('carpentryDocuments/getRecursos/{gestion}','CarpentryDocumentsController@getListRecursos');
    Route::post('carpentryDocuments/encargado/add','CarpentryDocumentsController@storeEncargado');
    Route::post('carpentryDocuments/asset/add','CarpentryDocumentsController@storeAsset');

    // *** - rutas para crear, editar, mostrar, buscar e imprimir a una persona - ***
    Route::post('persons', 'GeneralController@getPersonsByDescription');
    Route::get('person/add','GeneralController@addPerson');
    Route::post('person','GeneralController@storePerson');    
    //Route::get('person/edit/{id}','GeneralController@editPerson');    
    Route::get('person/{id}','GeneralController@getPersonById');    

    /*
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    */
    Route::post('upload', 'FileController@uploadFile');
});