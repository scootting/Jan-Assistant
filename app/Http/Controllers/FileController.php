<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index(){
        return Storage::files('upload');
    }

    public function uploadFile( Request $request){
        \Log::info($request);
        $dataSource = $request->get('datasource');
        $arrayData = json_decode($dataSource, true);;    
        \Log::info($arrayData);
        $mes = $arrayData['mes'];
        $gestion = $arrayData['gestion'];    

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
            \Log::info($file_name);
            $path = 'upload/'.strval($gestion).'/'.strval($mes);
            $file->storeAs($path, $file_name);
            /* Storage::disk('local')->put($file_name, $file); */
        } else {
            return response()->json(['error'=>'File not exist!']);
        }
        return response()->json(['success'=>'Uploaded Successfully.']);
    }

    //Upload multiple files
/*     public function uploadFile( Request $request){
        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach($files as $file){
                $file_name = $file->getClientOriginalName();
                $file->storeAs('upload', $file_name);
            }

        } else {
            return response()->json(['error'=>'File not exist!']);
        }
        return response()->json(['success'=>'Uploaded Successfully.']);
    } */

    public function downloadFile($file){
        $path = '../storage/app/upload/'.$file;
        $header = [
            'Content-Type' => 'application/*',
        ];
        return response()->download($path, $file, $header);
    }

    public function deleteFile(Request $request, $file){
        \Log::info("funcion delete");

        \Log::info($request);
        $file_exist = '../storage/app/upload/'.$file;
        if($file_exist){
            @unlink($file_exist);

        } else {
            return response()->json(['error'=>'File not exist!']);
        }
        return response()->json(['success'=>'Uploaded Successfully.']);
    }
}