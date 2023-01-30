<?php

namespace App\Http\Controllers;

use App\Document;
use App\Libraries\JSRClient;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DocumentController extends Controller
{

    //  *  D1. Obtener la lista de las solicitadas en linea por persona
    //  * {gestion: gestion activa}
    public function getRequests(Request $request)
    {
        $persona = $request->get('client');
        $id = strtoupper($persona['nodip']);
        $gestion = $request->get('year');
        $data = Document::GetRequests($id, $gestion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/request')]
        );
        return json_encode($paginate);
    }

    //  *  D2. Guardar los boucher generados por cada solicitud
    //  * {boucher: imagen del boucher }
    //  * {request: informacion del boucher }
    public function storeBoucherOfRequest(Request $request)
    {
        \Log::info($request);
        $solicitud = $request->get('tag');
        $boucher = $request->get('boucher');
        $fecha = $request->get('fecha');
        $monto = $request->get('monto');
        $ruta = "";
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
            $path = "treasure/" . strval($fecha) . '.' . strval($boucher);
            $ruta = $path . "/" . $file_name;
            $file->storeAs($path, $file_name);
            $file->store('treasure');
        } else {
            return response()->json(['error' => 'File not exist!']);
        }
        $data = Document::StoreBoucherOfRequest($solicitud, $boucher, $fecha, $monto, $ruta);
        return response()->json(['success' => 'Uploaded Successfully.']);
    }

    public function getDataRequestById(Request $request)
    {
        $id_sol = $request->get('id');
        $data = Document::GetDataRequestById($id_sol);
        $boucher = Document::getBoucherRequestById($id_sol);
        return json_encode(['data' => $data, 'boucher' => $boucher]);
    }

    //  * M2. Lista las solicitudes de elaboracion de memorial universitario
    public function getRequestsMemorial(Request $request)
    {
        $persona = $request->get('client');
        $id = strtoupper($persona['nodip']);
        $data = Document::GetRequestsMemorial($id);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/request')]
        );
        return json_encode($paginate);
    }

    //  * M1. guarda las solicitudes de memoriales solicitadas
    public function storeRequestMemorial(Request $request)
    {
        $client = $request->get('client');
        $ci_per = strtoupper($client['nodip']);
        $des_per = strtoupper($client['descripcion']);
        $gestion = $client['gestion'];

        $adquirido = $request->get('acquired');
        \log::info($adquirido);
        foreach ($adquirido as $item) {
            # code...
            $id = $item['id'];
            $abrv = $item['abrv'];
            $tipo = $item['tipo'];
            $data = Document::StoreRequestMemorial($id, $abrv, $tipo, $ci_per, $des_per, $gestion);
        }
        return json_encode($id);
        //return response()->json(['success' => 'Uploaded Successfully.']);
    }

    //  * M3. Imprimir la solicitud de elaboracion de memorial universitario
    public function reportRequestMemorial(Request $request)
    {
        $nro_com = $request->get('voucher');
        $tip_tra = $request->get('tipo');
        $gestion = $request->get('gestion'); //$dataDays['gestion'];
        \Log::info("DATOS PARA LA IMPRESION DE BOUCHER");
        \Log::info($gestion);
        \Log::info($tip_tra);
        \Log::info($nro_com);

        $nreport = 'DetailCreditSaleLetter';
        $controls = array(
            'p_nro_com' => trim($nro_com),
            'p_tip_tra' => $tip_tra,
            'p_gestion' => $gestion,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  * M4. Obtener la lista de memoriales habilitados para su seleccion
    public function getTypesOfMemorials(Request $request)
    {
        $gestion = $request->get('year');
        $data = Document::GetTypesOfMemorials($gestion);
        return json_encode($data);
    }

}
