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
