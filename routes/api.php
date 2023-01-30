<?php

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

Route::get('persona', 'GeneralController@getPersonByCI');

//  *  A1. Acceder a la plataforma ingresando el nro de ci y fecha de nacimiento
//  * {username: carnet de identidad del usuario, password: fecha de nacimiento del usuario o personalizado}
Route::post('login', 'GeneralController@loginClient');

//  *  A4. Registrar la informacion personal del cliente
//  * {cliente: array con la informacion personalizada del cliente}
//Route::post('registerPersonInformation', 'GeneralController@storePersonInformation');

Route::group([
    'middleware' => 'jwt.auth',
], function () {

    //  *  A2. Actualizar la informacion personal del cliente
    //  * {cliente: array con la informacion personalizada del cliente}
    Route::post('storePersonInformation', 'GeneralController@storePersonInformation');

    //  *  A3. cambiar la contraseña personal del cliente
    //  * {pass_ant: password anterior, pass_act: password nuevo, pass_con: password confirmado}
    Route::post('updatePersonPassword', 'GeneralController@updatePersonPassword');

    Route::post('logout', 'GeneralController@logoutUser');

    //  *  T1. Obtener los valores para la venta en linea
    //  * {gestion: gestion de los valores disponibles}
    Route::post('getValuesOffered', 'TreasureController@getValuesOffered');

    //  *  T2. Guardar los valores para la venta en linea
    //  * {cliente: informacion del cliente}
    //  * {valores: valores seleccionados}
    Route::post('setValuesAcquired', 'TreasureController@setValuesAcquired');
 
    //  *  D1. Obtener la lista de las solicitadas en linea por persona
    //  * {gestion: gestion activa}
    Route::post('request', 'DocumentController@getRequests');

    //  *  D2. Guardar los boucher generados por cada solicitud
    //  * {boucher: imagen del boucher }
    //  * {request: informacion del boucher }
    Route::post('storeBoucherOfRequest', 'DocumentController@storeBoucherOfRequest');

    //  *  D3. Obtener la informacion por cada solicitud
    //  * {id: id de la solicitud }
    Route::post('getDataRequestById', 'DocumentController@getDataRequestById');

    //  |--------------------------------------------------------------------------
    //  | Rutas API para el Sistema de Memoriales Universitarios
    //  |--------------------------------------------------------------------------    
    //  * M2. Lista las solicitudes de elaboracion de memorial universitario              
    Route::post('getRequestsMemorial', 'DocumentController@getRequestsMemorial');
    //  * M1. guarda las solicitudes realizadas             
    Route::post('storeRequestMemorial', 'DocumentController@storeRequestMemorial');
    //  * M3. Imprimir la solicitud de elaboracion de memorial universitario              
    Route::get('reportRequestMemorial', 'DocumentController@reportRequestMemorial');
    //  * M4. Obtener la lista de memoriales habilitados para su seleccion               
    Route::post('getTypesOfMemorials', 'DocumentController@getTypesOfMemorials');






    Route::post('profiles', 'GeneralController@registerUserProfiles');
    // *** - Buscar - ***
    Route::post('persons', 'GeneralController@getPersonsByDescription');

    // *** - Aniadir - ***
    Route::get('person/add', 'GeneralController@addPerson');
    // *** - Almacenar - ***
    Route::get('person/{id}', 'GeneralController@getPersonById');

    // *** opciones de postulacio ***
    Route::get('convocatoria', 'GeneralController@getDesDoc');

    // *** ver detalle de documento de persona***
    Route::get('detalleDoc', 'GeneralController@getTransaccionOrdenada');

    // *** ver documentos por personas ***
    Route::get('tipoDoc', 'GeneralController@getSolDoc');

    Route::post('seleccionar', 'GeneralController@postSeleccionarConvocatoria');

    // *** mostrat todos los cursos postgrado que existe***
    Route::get('curso', 'GeneralController@getValMat');

    /*
Route::post('upload', 'FileController@uploadFile');
 */
});
