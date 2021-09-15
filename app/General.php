<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as Collection;
use App\Libraries\DynamicMenu;

use function GuzzleHttp\Promise\queue;

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
        \Log::info("Description: ".$description);
        if ($description == '') 
            # code...
            $query = "select * from public.ff_buscar_personas('')";
        else
            $query = "select * from public.ff_buscar_personas('".$description."')";
        \Log::info($query);
        //$query = "select * from public.personas where paterno ='".$description."' order by paterno, materno, nombres";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

    // *** - funcion para la creacion de personas - ***
    // *** - parametros [carnet de identidad, nombres, apellido paterno, apellido materno, sexo, fecha de nacimiento] - ***
    public static function AddPerson($identityCard, $names, $paternal, $maternal, $sex, $birthdate){
        $query = "select * from public.ff_registrar_persona('".$identityCard."', '".$paternal."', '".$maternal."', '".$names."','".$sex."', '".$birthdate."')";
        //\Log::info("REGISTRAR PARA PERSONAS: ".$query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

    // *** - funcion para modificar informacion de las personas - ***
    // *** - parametros [carnet de identidad, nombres, apellido paterno, apellido materno, sexo, fecha de nacimiento] - ***
    public static function UpdatePerson($identityCard, $names, $paternal, $maternal, $sex, $birthdate){
        $query = "select * from public.ff_editar_persona('".$identityCard."', '".$paternal."', '".$maternal."', '".$names."','".$sex."', '".$birthdate."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

    public static function GetPersonByIdentityCard($identityCard){
        $query = "select * from public.ff_mostrar_persona('".$identityCard."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

    
    // *** - funcion para la busqueda de los usuarios  [db: app.users]- ***
    // *** - parametros [description: descripcion de la busqueda] - ***
    public static function GetUsersByDescription($description){
        if ($description == '') 
            # code...
            $query = "select * from app.users order by nodip, descripcion, usuario";
        else
            $query = "select * from app.users where nodip ='".$description."' order by nodip, descripcion, usuario";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));    
        return $data;
    }    


    // *** - funcion para la busqueda de las personas por carnet de identidad - ***
    // *** - parametros [carnet de identidad] - ***

    public static function GetUserByIdentityCard($identityCard){
        $query = "select * from public.ff_mostrar_usuario('".$identityCard."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }      
    
    // funcion para buscar a la persona por su carnet

    public static function getPersonByCI($nro_dip)
    {
        $query="select nro_dip,paterno,materno,nombres,fec_nacimiento,id_sexo,direccion,telefono,correo from public.personas where nro_dip like '%". $nro_dip ."%'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //funcion para guardar a una persona nueva
    public static function saveNewPerson ($nro_dip,$nombres,$paterno,$materno,$nacimiento,$sexo,$telefono,$direccion,$correo)
    {
        $query = "select * from public.guardar_nueva_persona('".$nro_dip."','".$nombres."','".$paterno."','".$materno."',
        '".$nacimiento."','".$sexo."','".$telefono."','".$direccion."','".$correo."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
