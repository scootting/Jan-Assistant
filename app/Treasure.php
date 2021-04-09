<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Treasure extends Model
{
    //  * Encontrar a un estudiante nuevo a traves de su carnet de identidad.
    //  * {id: numero de carnet de identidad}      
    //  * {year: gestion}      
    public static function getDataOfStudentById($id, $year){
        //select * from cluster.f_nuevos_datacenter('10547123', '2019', '2')
        $query = "select * from cluster.f_nuevos_datacenter('".$id."','".$year."','1') where r_aceptado = 'ACEPTADO'";
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
            $query = "select * from val.diario vd where vd.usr_cre = '".$user."' and vd.gestion = '".$year."' order by vd.id_dia desc";
        else
            $query = "select * from val.diario vd where vd.usr_cre = '".$user."' and vd.gestion = '".$year."' order by vd.id_dia desc";
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

    public static function addSaleOfDay($user, $year){
        //insert into val.diario(fec_tra, glosa, estado, tip_mon, importe, gestion, nro_com_min, usr_cre) 
        //               values (now(), 'Venta: De La Universidad Autónoma "Tomás Frías" En BOLIVIANOS', 'C', 'B', 0, 2021,'-1', 'rcallizaya');
        $query = "insert into val.diario(fec_tra, glosa, estado, tip_mon, importe, id_lugar, gestion, tip_tra, nro_com_min, usr_cre)".
                 "values (now(), 'Venta: De La Universidad Autónoma \"Tomás Frías\" En BOLIVIANOS', 'C', 'B', 0, 'U', '".$year."', 0, '-1', '".$user."')";        
        $data = collect(DB::select(DB::raw($query)));    
        return $data;
    }
    
    public static function addTransactionsByStudents($id_dia, $cod_val, $can_val, $pre_uni, $fec_tra, $usr_cre, $nro_com, $ci_per, $des_per, $tip_tra, $gestion){        
        //insert into val.tra_dia( ... ) values ( ... ) RETURNING id_tran
        $query = "INSERT INTO val.tra_dia(id_dia, cod_val, can_val, pre_uni, fec_tra, usr_cre,".
                 "nro_com, ci_per, des_per, tip_tra, gestion) VALUES ". 
                 "('" .$id_dia. "','" .$cod_val. "','" .$can_val. "','" .$pre_uni."','" .$fec_tra. "','" .$usr_cre. "','" .
                 $nro_com. "','". $ci_per. "','" .$des_per. "','10','" .$gestion. "') RETURNING id_tran";
        $data = collect(DB::select(DB::raw($query))); 
        return $data;
    }

    public static function getIdTransactionsByYear($gestion){
        $query = "select * from trap.ff_id_tramite('" .$gestion. "')";
        $data = collect(DB::select(DB::raw($query))); 
        \Log::info("este es el numero de tramite: ". $data);
        return $data;
    }

    public static function addProcedureByStudents($id_dia, $id_tran, $nro_com, $cod_val, $ci_per, $des_per, $idx, $gestion, $imp_val){
        //insert into trap.tram( ... ) values ( ... )
        $query = "INSERT INTO trap.tram(id_dia, id_tran, nro_com, cod_val, ".
                 "ci_per, des_per, idx, gestion, imp_val) VALUES ". 
                 "('" .$id_dia. "','" .$id_tran. "','" .$nro_com. "','" .$cod_val. "','" .
                 $ci_per. "','" .$des_per. "','" .$idx. "','" .$gestion. "','" .$imp_val. "')";
        $data = collect(DB::select(DB::raw($query))); 
        return $data;

    } 


}
