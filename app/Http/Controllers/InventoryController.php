<?php

namespace App\Http\Controllers;

use App\Inventory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPJasper\PHPJasper;

class InventoryController extends Controller
{
    public function getOffices(Request $request, $gestion)
    {
        $descripcion = ($request->get('descripcion') ? $request->get('descripcion') : '');
        $data = Inventory::getOffices($gestion, $descripcion);
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
        $idso = ($request->get('idSubOffice')) ? $request->get('idSubOffice') : null;
        $data = Inventory::getActivosByCodSoaAndSubOffice($cod_soa, $idso);
        $page = ($request->get('page')) ? $request->get('page') : null;
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory/activos/' . $cod_soa)]
        );
        return json_encode($paginate);
    }

    public function getReport()
    {
        $input = base_path() .
        '/reports/test.jrxml';
    
        $jasper = new PHPJasper;
        $jasper->compile($input)->execute();
    
        $input = base_path() .
            '/reports/test.jasper';
        $output = base_path() .
            '/public/reports';
            
        $options = [
            'format' => ['pdf'],
        ];/*
        $options = [
            'format' => ['pdf'],
            'locale' => 'en',
            'params' => [],
            'db_connection' => [
                'driver' => env('DB_CONNECTION'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'host' => env('DB_HOST'),
                'database' => env('DB_DATABASE'),
                'port' => env('DB_PORT')
            ],/*            
            'db_connection' => [
                'driver' => 'postgres', //mysql, ....
                'username' => 'postgres',
                'password' => '12345678',
                'host' => '192.168.25.64',
                'database' => 'daf_help',
                'port' => '5432'
            ]
        ];*/

        $jasper = new PHPJasper;

        $jasper->process(
            $input,
            $output,
            $options
        )->execute();

        $pathToFile = base_path() .
            '/public/reports/test.pdf';
        return response()->file($pathToFile);
    }
}
