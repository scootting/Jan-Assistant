<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\JWTFAuth;
use App\Libraries\DynamicMenu;
use App\General;

use Illuminate\Pagination\LengthAwarePaginator;

class GeneralController extends Controller
{
    //
    //
    //  * Buscar a un usuario de el recurso.
    //  * {username: nombre de usuario, password: clave del usuario}    
    public function searchUser(Request $request){  
        $data = General::SearchUser($request->get('username'), $request->get('password'));
        $token = JWTFAuth::ValidateDataCredential($data);
        $response = array(
            'access_token' => $token,
            'user' => $data
        );        
        return json_encode($response); 
    } 

    //  * Quitar el registro de un usuario en el recurso.    
    public function logoutUser(Request $request){
        return response()->json('Logged out successfully', 200);
    }

    //  * Registrar un usuario en el recurso.    
    public function registerUser(Request $request){
        $carnet = $request->get('carnet'); 
        $data = General::RegisterUser($carnet);
        return $data;
    } 
    
    public function registerUserProfiles(Request $request){
        $usuario = $request->get('usuario');
        $gestion = $request->get('gestion');
        $data = General::SearchUserProfiles($usuario, $gestion);
        $profiles = DynamicMenu::GetDataOptions($data);
        return json_encode($profiles);
    }

    public function registerUserYears(Request $request){
        $usuario = $request->get('usuario');
        $years = General::SearchUserYears($usuario);
        return json_encode($years);
    }

    public function getPersonsByDescription(Request $request){
        $descripcion = $request->get('descripcion'); // '' cadena vacia
        $parametro = $request->get('parametro');
        $descripcion = 'ROJAS';
        $data = General::GetPersonsByDescription($descripcion);
        //\Log::info("Requesta: ".$request);
        
        $page = ($request->get('page')? $request->get('page'): 1);
        $perPage = 10;

        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/persons')]
        );
        //\Log::info("Data Personas: ".$paginate);
        /*
        return json_encode([
            'pagination' => [
                'total' => $items->total(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem()
            ],
            'data' => $items]);*/
        return json_encode($paginate);
    }

}