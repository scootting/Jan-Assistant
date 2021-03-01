<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Treasure extends Model
{
    //  * Encontrar a un estudiante nuevo a traves de su carnet de identidad.
    //  * {id: numero de carnet de identidad}      
    //  * {year: gestion}      
    public static function getDataOfStudentById($id, $year){
        //select * from cluster.f_nuevos_datacenter('10547123', '2019', '2')
        $query = "select * from cluster.f_nuevos_datacenter('".$id."','".$year."','1')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

    //  * Encontrar los valores de un tramite a traves de su descripcion.
    //  * {description: descripcion del tramite}      
    //  * {year: gestion}      
    public static function getValuesProcedure($description, $year){
        //select * from trap.ff_valores_tramite('EXCELENCIA', '2020')
        $query = "select * from trap.ff_valores_tramite('".$description."','".$year."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }  
    
    //  * Encontrar los valores de un tramite a traves de su descripcion.
    //  * {description: descripcion de la busqueda}
    //  * {user: usuario}
    //  * {year: gestion}     
    public static function getSaleOfDaysByDescription($description, $user, $year){
        //select * from val.diario vd where vd.usr_cre = 'rcallizaya' and vd.gestion = '2020'
        if ($description == '') 
            $query = "select * from val.diario vd where vd.usr_cre = '".$user."' and vd.gestion = '".$year."'";
        else
            $query = "select * from val.diario vd where vd.usr_cre = '".$user."' and vd.gestion = '".$year."'";
        $data = collect(DB::select(DB::raw($query)));    
        return $data;
    }

    //  * Encontrar los valores de un tramite a traves de su descripcion.
    //  * {id: id del dia que se va a utilizar}
    //  * {user: usuario}
    //  * {year: gestion}     
    public static function getSaleOfDayById($id, $user, $year){
        //select * from val.diario vd where vd.id_dia = '1234' and vd.usr_cre = 'rcallizaya' and vd.gestion = '2020'
        $query = "select * from val.diario vd where vd.id_dia = '".$id."' and vd.usr_cre = '".$user."' and vd.gestion = '".$year."'";
        $data = collect(DB::select(DB::raw($query)));    
        return $data;
    }
    public static function addTransactionsByStudents($id_dia, $cod_val, $can_val, $pre_uni, $fec_tra, $usr_cre, $nro_com, $ci_per, $des_per, $tip_tra, $gestion){
        
        \Log::info("Entra aca por si acaso");
        //insert into val.tra_dia( ... ) values ( ... ) RETURNING id_tran
        $query = "INSERT INTO val.tra_dia(id_dia, cod_val, can_val, pre_uni, fec_tra, usr_cre,".
                 "nro_com, ci_per, des_per, tip_tra, gestion) VALUES ". 
                 "('" .$id_dia. "','" .$cod_val. "','" .$can_val. "','" .$pre_uni."','" .$fec_tra. "','" .$usr_cre. "','" .
                 $nro_com. "','". $ci_per. "','" .$des_per. "','10','" .$gestion. "') RETURNING id_tran";
        $data = collect(DB::select(DB::raw($query))); 
        \Log::info("Data". $data);
        return $data;
    }

}
/*
id_dia INTEGER,
  id_tran SERIAL,
  cod_val CHAR(5),
  can_val NUMERIC(8,0),
  pre_uni NUMERIC(10,2),
  imp_val NUMERIC(10,2),
  desde INTEGER DEFAULT 0,
  hasta INTEGER DEFAULT 0,
  fec_tra DATE,
  usr_cre VARCHAR(15),
  fec_cre TIMESTAMP WITH TIME ZONE DEFAULT now(),
  nro_com CHAR(6) DEFAULT ''::bpchar,
  ci_per CHAR(15),
  des_per VARCHAR(80),
  obs TEXT,
  tip_tra SMALLINT DEFAULT 0,
  tra_imp SMALLINT DEFAULT 0,
  gestion SMALLINT,
  tra_ver SMALLINT DEFAULT 0
*/