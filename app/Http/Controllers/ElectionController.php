<?php

namespace App\Http\Controllers;

use App\Election;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    //

    //  * E1 . Obtener la persona por su carnet de identidad y la mesa donde deberia realizar su boto
    //  * parametros {id: numero de carnet de identidad de la persona, id_election:  id de la eleccion que se esta realizando}
    public function getAuthorizedPerson(Request $request)
    {
        $id = $request->get('id');
        $id_election = $request->get('id_election');
        $id_election = 2;
        //  * Recupera la informacion de la persona y si se encuentra habilitada o no
        $dataPerson = Election::GetInformationPerson($id, $id_election);
        //\log::info($dataPerson);
        //  * Recupera la informacion de la mesa en la cual deberia votar la persona
        $dataTablet = Election::GetInformationTabletByPerson($id, $id_election);
        return json_encode(['dataPerson' => $dataPerson, 'dataTablet' => $dataTablet]);
    }
    //  * E3 . Obtener la lista de mesas habilitadas para la eleccion
    public function getInformationTablets(Request $request)
    {
        $id_election = $request->get('id_election');
        $id_election = 2;
        $dataTablets = Election::GetInformationTablets($id_election);
        return json_encode(['dataTablets' => $dataTablets]);
    }

}
