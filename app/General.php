<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class General extends Model
{
    //  *  A1. Acceder a la plataforma ingresando el nro de ci y fecha de nacimiento
    //  * {username: carnet de identidad del usuario, password: fecha de nacimiento del usuario o personalizado}
    public static function LoginClient($username, $password)
    {
        $query = "select * from pub.ff_login_persona('" . $username . "','" . $password . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  A2. Actualizar la informacion personal del cliente
    //  * {cliente: array con la informacion personalizada del cliente}    
    public static function UpdatePersonInformation($card, $names, $paternal, $maternal, $sex, $birthdate, $direction, $phone, $email)
    {
        $query = "select * from pub.ff_editar_persona('" . $card .  "', '" . $direction . "','" . $phone . "', '" . $email . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  A3. cambiar la contraseña personal del cliente
    //  * {pass_ant: password anterior, pass_act: password nuevo, pass_con: password confirmado}    
    public static function UpdatePersonPassword($card, $pass_actual, $pass_nuevo, $pass_confirma)
    {
        $query = "select * from pub.ff_contrasena_persona('" . $card . "', '" . $pass_actual . "', '" . $pass_nuevo . "', '" . $pass_confirma . "')";
        //\Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  A7. Obtiene la lista de categorias programaticas
    //  * {year: gestion en la que se desarrolla}
    public static function GetProgramaticCategory($year)
    {
        //$query = "select *, cat_des as value from public.sis_cat_pro d where d.cat_ano = '" . $year . "' and d.cat_pro = '10' and d.cat_sis = 'ACTIVIDAD'";
        $query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "' order by programa asc";
        //\Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetProgramaticCategoryUniversity($year)
    {
        $query = "select *, cat_des as value from public.sis_cat_pro d where d.cat_ano = '" . $year . "' and d.cat_pro in ('00','01','02','03', '10','51', '61') and d.cat_sis = 'ACTIVIDAD' order by cat_des asc";
        //\Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  A4. Registrar la informacion personal del cliente
    //  * {cliente: array con la informacion personalizada del cliente}    
    public static function RegisterPersonInformation($card, $names, $paternal, $maternal, $sex, $birthdate, $direction, $phone, $email)
    {
        $query = "select * from pub.ff_registrar_persona('" . $card . "', '" . $paternal . "', '" . $maternal . "', '" . $names . "','" . $sex . "', '" . $birthdate . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function SetValuesAndCptState($codigoTransaccion, $estado, $fecha)
    {
        $query = "select * from ppe.ff_registrar_transacciones('" . $codigoTransaccion . "', '" . $estado ."', '" . $fecha . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


    // *** - funcion para el registro de usuarios - ***
    // *** - parametros [dip: numero de carnet de identidad de la persona] - ***
    public static function RegisterUser($personal)
    {
        $query = "select * from app.ff_registrar_usuario('" . $personal . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - funcion para la busqueda de los perfiles del usuario - ***
    // *** - parametros [username: nombre de usuario de la persona, year: año de los perfiles] - ***
    public static function SearchUserProfiles($username, $year)
    {
        $query = "select * from app.ff_perfiles_usuario('" . $username . "','" . $year . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - funcion para la busqueda de las gestiones registradas para el usuario - ***
    // *** - parametros [username: nombre de usuario de la persona] - ***
    public static function SearchUserYears($username)
    {
        $query = "select * from app.ff_gestiones_usuario('" . $username . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetPersonsByDescription($description)
    {
        //\Log::info("Description: " . $description);
        if ($description == '')
        # code...
        {
            $query = "select * from public.ff_buscar_personas('')";
        } else {
            $query = "select * from public.ff_buscar_personas('" . $description . "')";
        }

        //\Log::info($query);
        //$query = "select * from public.personas where paterno ='".$description."' order by paterno, materno, nombres";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


    public static function GetPersonByIdentityCard($identityCard)
    {
        $query = "select * from public.ff_mostrar_persona('" . $identityCard . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - funcion para la busqueda de los usuarios  [db: app.users]- ***
    // *** - parametros [description: descripcion de la busqueda] - ***
    public static function GetUsersByDescription($description)
    {
        if ($description == '')
        # code...
        {
            $query = "select * from app.users order by nodip, descripcion, usuario";
        } else {
            $query = "select * from app.users where nodip ='" . $description . "' order by nodip, descripcion, usuario";
        }

        //\Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - funcion para la busqueda de las personas por carnet de identidad - ***
    // *** - parametros [carnet de identidad] - ***

    public static function GetUserByIdentityCard($identityCard)
    {
        $query = "select * from public.ff_mostrar_usuario('" . $identityCard . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // funcion para buscar a la persona por su carnet

    public static function getPersonByCI($nro_dip)
    {
        $query = "select nro_dip,paterno,materno,nombres,fec_nacimiento,id_sexo,direccion,telefono,correo from public.personas where nro_dip like '%" . $nro_dip . "%'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //funcion para guardar a una persona nueva
    public static function AddPerson($nro_dip, $nombres, $paterno, $materno, $nacimiento, $sexo, $telefono, $direccion, $correo)
    {
        $query = "select * from public.ff_registrar_persona_extra('" . $nro_dip . "', '" . $paterno . "', '" . $materno . "', '" . $nombres . "','" . $sexo . "', '" . $nacimiento. "', '" . $telefono . "', '" . $direccion . "', '" . $correo. "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getDesDoc()
    {
        $query = " select * from bdoc.des_doc ";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function getTransaccionOrdenada($tag, $gestion)
    {
        $query = " select * from bdoc.ff_transaccion_ordenada('" . $tag . "','" . $gestion . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getSolDoc($ci_per)
    {
        $query = " select s.tag_doc,s.est_sol ,
        (select dc.glosa from bdoc.des_doc dc where dc.cod_con = s.id_doc ) as des_doc
         from bdoc.sol_doc s where s.ci_per like '%" . $ci_per . "%' ";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function postSeleccionarConvocatoria($cod, $ci, $per)
    {
        $query = "select * from bdoc.ff_insertar_convocatoria('" . $cod . "','" . $ci . "','" . $per . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function valorMaterial($keyWord)
    {
        $query = "select * from bdoc.val_mat where bdoc.val_mat.des_dip like '%" . $keyWord . "%'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
