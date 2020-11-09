<?php

namespace App\Http\Controllers;

use App\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryController extends Controller
{
    public function getOffices(Request $request, $gestion)

    {
        $descripcion = ($request->get('descripcion') ? $request->get('descripcion') : '');
        $data = Inventory::getOffices($gestion,$descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;

        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory')]
        );
        return json_encode($paginate);
    }
    public function getOfficeByCodSoa($cod_soa)
    {
        $data = Inventory::getOfficeByCodSoa($cod_soa);
        return json_encode($data);
    }
    public function getSubOfficesByCodSoa($cod_soa)
    {
        $data = Inventory::getSubOfficesByCodSoa($cod_soa);
        return json_encode($data);
    }
    public function getActivosByCodSoaAndSubOffice(Request $request, $cod_soa)
    {
        $idso = ($request->get('idSubOffice'))? $request->get('idSubOffice') : null; 
        $data = Inventory::getActivosByCodSoaAndSubOffice($cod_soa,$idso);
        $page = ($request->get('page'))? $request->get('page') : null;
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory/activos/'.$cod_soa)]
        );
        return json_encode($paginate);
    }
}
