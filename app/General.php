<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as Collection;
use App\Libraries\DynamicMenu;

class General extends Model
{
    // *** - funcion para la busqueda de usuarios - ***
    // *** - parametros [username: nombre de usuario, password: clave del usuario] - ***
    public static function SearchUser($username, $password){
        $query = "select * from app.ff_login_usuario('".$username."','".$password."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    
    // *** - funcion para el registro de usuarios - ***
    // *** - parametros [dip: numero de carnet de identidad de la persona] - ***
    public static function RegisterUser($personal){
        $query = "select * from app.ff_registrar_usuario('".$personal."')";        
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - funcion para la busqueda de los perfiles del usuario - ***
    // *** - parametros [username: nombre de usuario de la persona, year: año de los perfiles] - ***
    public static function SearchUserProfiles($username, $year){
        $query = "select * from app.ff_perfiles_usuario('".$username."','".$year."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - funcion para la busqueda de las gestiones registradas para el usuario - ***
    // *** - parametros [username: nombre de usuario de la persona] - ***
    public static function SearchUserYears($username){
        $query = "select * from app.ff_gestiones_usuario('".$username."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function SearchPersons($description){
        $query = "select * from public.personas where paterno ='".$description."'";

        \Log::info("Query: ".$query);

        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    
}
