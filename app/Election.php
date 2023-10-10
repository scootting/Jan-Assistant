<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Election extends Model
{
    //  * Recupera la informacion de la persona y si se encuentra habilitada o no
    public static function GetInformationPerson($id, $id_election)
    {
        $query = "select * from econ.ff_datos_persona('" . $id . "','" . $id_election . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * Recupera la informacion de la mesa en la cual deberia votar la persona
    public static function GetInformationTabletByPerson($id, $id_election)
    {
        $query = "select * from econ.ff_datos_persona_mesa('" . $id . "','" . $id_election . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * Recupera la informacion de la mesa en la cual deberia votar la persona
    public static function getInformationTablets($id_election)
    {
        $query = "select * from econ.mesas where id_claustro = '" . $id_election . "' order by numero asc";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
