<?php

namespace App\Http\Controllers;

use App\Document;
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

}
