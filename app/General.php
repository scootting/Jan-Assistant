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

    public static function GetPersonsByDescription($description){
        $query = "select * from public.personas where paterno ='".$description."' order by paterno, materno, nombres";
        //\Log::info("Query: ".$query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

    public static function AddPerson($identityCard, $names, $paternal, $maternal, $sex, $birthdate){
        $query = "select * from public.ff_registrar_persona('".$identityCard."', '".$paternal."', '".$maternal."', '".$names."','".$sex."', '".$birthdate."')";
        //\Log::info("REGISTRAR PARA PERSONAS: ".$query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

    public static function UpdatePerson($identityCard, $names, $paternal, $maternal, $sex, $birthdate){
        $query = "select * from public.ff_editar_persona('".$identityCard."', '".$paternal."', '".$maternal."', '".$names."','".$sex."', '".$birthdate."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

    public static function GetPersonByIdentityCard($identityCard){
        $query = "select * from public.ff_buscar_persona('".$identityCard."')";
        //$data = DB::table('public.personas')->where('nro_dip','=',"6600648")->get();
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

}
