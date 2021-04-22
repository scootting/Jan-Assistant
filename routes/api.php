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

Route::post('login', 'GeneralController@searchUser');

Route::group([
    'middleware' => 'jwt.auth',
], function () {
    Route::post('logout', 'GeneralController@logoutUser');
    Route::post('profiles', 'GeneralController@registerUserProfiles');
    Route::post('years', 'GeneralController@registerUserYears');

    // *** - rutas para crear, editar, mostrar, buscar a las personas - ***
    // *** - Buscar - ***
    Route::post('persons', 'GeneralController@getPersonsByDescription');
    // *** - Aniadir - ***
    Route::get('person/add', 'GeneralController@addPerson');
    // *** - Almacenar - ***
    Route::post('person', 'GeneralController@storePerson');
    Route::get('person/{id}', 'GeneralController@getPersonById');


    // *** - rutas para crear, editar, mostrar, buscar a los usuarios del sistema - ***
    Route::post('users', 'GeneralController@getUsersByDescription');
    Route::post('user', 'GeneralController@storeUser');
    Route::get('user/{id}', 'GeneralController@getUserById');
    /*
    Route::post('upload', 'FileController@uploadFile');
    */
    Route::get('inventory/{gestion}', 'InventoryController@getOffices');
    Route::get('inventory/show/{cod_soa}', 'InventoryController@getOfficeByCodSoa');
    Route::get('inventory/sub_offices/{cod_soa}', 'InventoryController@getSubOfficesByCodSoa');
    Route::get('inventory/cargos/{cod_soa}', 'InventoryController@getRespToCargosByCodSoa');
    Route::get('inventory/activos/{cod_soa}', 'InventoryController@getActivosByCodSoaAndSubOffice');
    Route::get('inventory/activosByResp/{cod_soa}', 'InventoryController@getActivosBySoaAndResp');
    Route::get('descargando/{cod_soa}', 'InventoryController@getReport');
    Route::get('inventarioDetalle/', 'InventoryController@getReportDetalle');
    Route::get('inventarioGeneral/', 'InventoryController@getReportGeneral');
    Route::get('generarReporte/', 'InventoryController@getGenerarReporte');
    
    //rutas de inventarios 2 
    Route::get('inventory2/unidad', 'InventoryController@getUnidad');
    Route::get('inventory2/sub_unidad', 'InventoryController@getSubUnidad');
    Route::get('inventory2/cargos', 'InventoryController@getCargos');
    Route::get('inventory2/responsables', 'InventoryController@getResponsables');
    Route::get('inventory2/responsables2', 'InventoryController@getResponsablesByUnidad');
    Route::get('inventory2/encargados', 'InventoryController@getEncargados');
    Route::post('inventory2/save', 'InventoryController@saveNewInventory');
    Route::get('inventory2/{gestion}', 'InventoryController@getInventories');
    Route::get('inventory2/edit/{id}','InventoryController@getInventory');
    Route::post('inventory2/saveChange ','InventoryController@saveChangeDocInventory');
    Route::get('inventory2/doc_inv/{no_cod}', 'InventoryController@showDocInventory');
    Route::get('inventory2/doc_detail_by_active/{id}', 'InventoryController@getDocDetailByActivoId');
    Route::get('inventory2/search/{doc_cod}', 'InventoryController@getActivesForDocInv');
    Route::get('activo/estados','InventoryController@getEstados');
    Route::post('inventory2/saveActive','InventoryController@saveActiveInDetailDoc');
    //***rutas de re asignacion de activos***
    Route::get('reasignacion/', 'InventoryController@SearchActivo'); 
    Route::get('reasignacion/edit/{id}','InventoryController@getActive');
    Route::post('reasignacion/save','InventoryController@saveChangeActive');
    


    // *** - Tesoreria - Rutas para la venta de alumnos nuevos - ***
    // *** - Buscar por su carnet de identidad - ***
    Route::post('getDataOfStudentById', 'TreasureController@getDataOfStudentById');
    // *** - Buscar los valores pertenecientes a un tramite - ***
    Route::post('valuesprocedure', 'TreasureController@getValuesProcedure');
    // *** - Obtener el reporte correspondiente a los valores vendidos para alumnos nuevos - ***
    Route::get('reports/{id_dia}/{ci_per}/{gestion}/{usr_cre}', 'TreasureController@getReportValuesQr');
    // *** - Obtener el reporte correspondiente a los valores vendidos para alumnos nuevos por dia - ***
    Route::get('reportDetailStudents/{id}', 'TreasureController@getReportDetailStudents');    
    // *** - Almacenar - ***
    Route::post('storeTransactionsByStudents', 'TreasureController@storeTransactionsByStudents'); 
    // *** - Obtener los dias para la venta de valores de un usuario - ***
    Route::post('getSaleOfDaysByDescription', 'TreasureController@getSaleOfDaysByDescription'); 
    Route::post('addSaleOfDay', 'TreasureController@addSaleOfDay');
    // *** - Obtener los dias para la venta de valores de un usuario - ***
    Route::post('getSaleOfDayById', 'TreasureController@getSaleOfDayById');

    // *** -  - ***
    Route::post('documentFixedAssets', 'FixedAssetController@getDocumentFixedAssetByYear');
    // *** -  - ***
    Route::post('selectedFixedAssetsbyDocument', 'FixedAssetController@getFixedAssetsbyDocument');
    //Route::get('reportSelectedFixedAssets/{id}', 'FixedAssetController@getReportSelectedFixedAssets');
    Route::get('reportSelectedFixedAssets/', 'FixedAssetController@getReportSelectedFixedAssets');
});
