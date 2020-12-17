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
    //reportes usando Jasper
    public function getReport(Request $request, $cod_soa)
    {
        \Log::info($cod_soa);
        $input = public_path() .
            '/reports/assets.jrxml';

        $jasper = new PHPJasper;
        $jasper->compile($input)->execute();
        $input = public_path() .
            '/reports/assets.jasper';
        $output = public_path() .
            '/reports';
        $options = [
            'format' => ['pdf'],
            'params' => [
                'p_ofc_cod' => $cod_soa,
            ],
            'db_connection' => [
                'driver' => 'postgres', //mysql, ....
                'username' => 'postgres',
                'password' => '12345678',
                'host' => '192.168.25.64',
                'database' => 'daf_help',
                'port' => '5432',
            ],/**/ 
        ];
        /*
        'db_connection' => [
        'driver' => env('DB_CONNECTION'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'host' => env('DB_HOST'),
        'database' => env('DB_DATABASE'),
        'port' => env('DB_PORT')
        ],*/

        //];
        $jasper = new PHPJasper;
        \Log::info($options);
        $jasper->process(
            $input,
            $output,
            $options
        )->execute();

        $pathToFile = public_path() .
            '/reports/assets.pdf';
        // Render
        //return response()->file($pathToFile);
        // Download
        //return response()->download($pathToFile);

        $filename = 'assets.pdf';
        $headers = ['Content-Type' => 'application/pdf'];
/*

        return Response::make(file_get_contents($pathToFile), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);*/
        \Log::info("pruebas de algo");
        \Log::info(response()->download($pathToFile, $filename, $headers));
        return response()->download($pathToFile, $filename, $headers);
    } 

   public function getInventories(Request $request,$gestion)
   {
    $descripcion = ($request->get('descripcion') ? $request->get('descripcion') : '');
    $data = Inventory::getInventories($gestion, $descripcion);
    $page = ($request->get('page') ? $request->get('page') : 1);
    $perPage = 10;
    $paginate = new LengthAwarePaginator(
        $data->forPage($page, $perPage),
        $data->count(),
        $perPage,
        $page,
        ['path' => url('api/inventory2')]
    );
    return json_encode($paginate);
   }
   public function getUnidad(Request $request)
   {
    $keyWord = ($request->get('keyWord') ? $request->get('keyWord') : '');
    $data = Inventory::getUnidad($keyWord);
    $page = ($request->get('page') ? $request->get('page') : 1);
    $perPage = 10;
    $paginate = new LengthAwarePaginator(
        $data->forPage($page, $perPage),
        $data->count(),
        $perPage,
        $page,
        ['path' => url('api/inventory2/unidad')]
    );
    return json_encode($paginate);
   }
   public function getSubUnidad(Request $request)
   {
    
    $unidad = ($request->get('cod_soa') ? $request->get('cod_soa') : '');
    $data = Inventory::getSubUnidades($unidad);
    return json_encode($data);
   }
   public function getCargos(Request $request)
   {
    $unidad = ($request->get('cod_soa') ? $request->get('cod_soa') : '');
    //$sub_unidades = ($request->get('sub_unidades') ? $request->get('sub_unidades') : []);
    $data = Inventory::getCargos($unidad); //$sub_unidades
    return json_encode($data);
   }
   public function getResponsables(Request $request)
   {
    $unidad = ($request->get('unidad') ? $request->get('unidad') : '');
    $cargos = ($request->get('cargos') ? $request->get('cargos') : []);
    $data = Inventory::getResponsables($unidad,$cargos);
    return json_encode($data);
   }
   public function getEncargados(Request $request)
   {    
     $nro_dip = ($request->get('nro_dip') ? $request->get('nro_dip') : '');
     $data = Inventory::getEncargados($nro_dip);
     $page = ($request->get('page') ? $request->get('page') : 1);
     $perPage = 10;
     $paginate = new LengthAwarePaginator(
         $data->forPage($page, $perPage),
         $data->count(),
         $perPage,
         $page,
         ['path' => url('api/inventory2/encargados')]
     );
     return json_encode($paginate);
    
   }
   public function saveNewInventory(Request $request)
   {
       //dd($request);
       $no_doc = '0008';//$request->no_doc;
       $res_enc = $request->encargados;
       $car_cod_enc = [];
       for($i=0;$i<count($res_enc); $i++)
        $car_cod_enc[]=3;
       $car_cod = $car_cod_enc;
       $ofc_cod = $request->unidad;
       $sub_ofc_cod = $request->subUnidades;
       $car_cod_resp = $request->cargos;
       $ci_res = $request->responsables;
       $gestion = '2020'; //$request->gestion;
       $data = Inventory::saveNewInventory($no_doc,$res_enc,$car_cod,$ofc_cod,$sub_ofc_cod,$car_cod_resp,$ci_res,$gestion);
       return json_encode($data);
   }
}
